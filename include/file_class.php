<?php
/**
* @author Richard Hayes <richard_c_hayes@yahoo.co.uk> (and special thanks to Igor Storozhev)
* @version	1.5
*
* Class DirOperator used to read, write and delete directories.
*
* <b>Current functionality</b> includes reading, writing, creating and deleting of directories.
* Regular expressions can be used for file/directory inclusion and exclusion.
*/
class DirOperator
{
	/**
	* @var array contents of directories
	* @access private
	*/
	var $contents = array();

	/**
	* @var string error messages
	* @access private
	*/
	var $error;

	/**
	* @var boolean Whether to display directories with output
	* @access private
	*/
	var $showDir = false;

	/**
	* @var boolean Whether to scan subdirectories
	* @access private
	*/
	var $sub = false;

	/**
	* @access public
	* @returns void
	*/
	function DirOperator()
	{
	}

	/**
	* Resets object to default values
	* @access public
	* @returns void
	*/
	function reset()
	{
		$this -> contents = array();
		$this -> error 	  = '';
		$this -> sub      = false;
		$this -> showDir  = false;
	}

	/**
	* Returns directory contents
	* @access public
	* @returns array
	*/
	function getContents()
	{
		return  $this -> contents;
	}

	/**
	* Scan directories for files.
	* @param string $path location of directory
	* @param string $inc reg-ex used for types of files to display.
	* @param string $exc reg-ex used for files and directories not to include with output
	* @access private
	* @returns boolean
	*/
	function scan($path, $inc = '', $exc = '')
	{
		$dp = dir($path);
		if(!$dp)
		{
			$this -> error .= "Couldn't open directory $path"; 
			return false;
		}

		while(($file = $dp -> read()) !== false)
		{
			if ((!is_dir($dp -> path.'/'.$file) && !empty($inc) && !ereg($inc, $file)) ||
			(!empty($exc) && ereg($exc, $file)) || 
			$file == '.' || $file == '..')
			{
				continue;
			}
			if (is_dir($dp -> path.'/'.$file))
			{
				if($this -> sub)
				{
					$dir[] = $file;
				}
				elseif($this -> showDir)
				{
					$this -> contents[] = array( $file, 
					filesize($dp -> path.'/'.$file),
					filemtime($dp -> path.'/'.$file),
					true);
				}
			}
			else
			{
				$this -> contents[] = array( $file, 
				filesize($dp -> path.'/'.$file),
				filemtime($dp -> path.'/'.$file),
				false);
			}
		}

		$dp -> close();

		for ($i = 0; $i < sizeof($dir); $i ++)
		{
			clearstatcache();

			$this -> scan($dp -> path.'/'.$dir[$i], $inc, $exc);
		}

		return true;
	}

	/**
	* Return files from directories.
	*
	* If variables: $inc or $exc are set they need to be enclosed in "double" quotes.
	* When $inc or $exc are not set All files/directories will be included  with output.
	* Example - 
	* <code>
	* $dir = new DirOperator();
	* $dir -> output('./', "(htm|html|gif)\$", "(.htaccess|phpMyAdmin|nude.jpeg)"));
	* </code>
	* The above code would only output .htm, .html and .gif files and .htaccess, nude.jpeg (files)
	* and phpMyAdmin directory would be ignored. If you are not familiar with regular-expressions, please take 
	* note of the syntax (|\$)
	* @param string $path location of directory
	* @param string $inc reg-ex used for types of files to display.
	* @param string $exc reg-ex used for files and directories not to include with output
	* @access public
	* @returns mixed
	*/
	function output($path, $inc = '', $exc = '')
	{
		if ($this -> scan($path, $inc, $exc))
		{
			return $this -> contents;
		}
		else
		{
			return '';
		}
	}

	/**
	* Include subdirectories to scan
	* @access public
	* @returns void 
	*/
	function setSubDir()
	{
		$this -> sub = true;
	}

	/**
	* Exclude subdirectories to scan
	* @access public
	* @returns void 
	*/
	function unsetSubDir()
	{
		$this -> sub = false;
	}

	/**
	* Display directories with output
	* @access public
	* @returns void 
	*/
	function showDir()
	{
		$this -> showDir = true;
	}

	/**
	* Hide directories from output
	* @access public
	* @returns void 
	*/
	function hideDir()
	{
		$this -> showDir = false;
	}

	/**
	* Create a directory
	* @param string $path directory/path/name
	* @param int $perm directory permissions 
	* @access public
	* @returns boolean
	*/
	function create($path, $perm = 0777)
	{
		if(!mkdir($path, $perm)) 
		{
			$this->error .= "Couldn\'t create directory $path perm=$perm ";
			return false;
		}
		return true;
	}

	/**
	* Deletes a directory
	* @param string $path directory/path/name
	* @access public
	* @returns boolean
	*/
	function delete($path)
	{
		if(!rmdir($path))
		{
			$this -> error .= "Couldn\'t delete directory $path ";
			return false;
		}
		return true;
	}
}
?>