<?php

Class FileSystem Extends Minimal {

	public $path = './data';
	public $pathStrip = false;
	public $extensionStrip = false;
	public $content;
	public $folders = array();
	public $files = array();
	public $fileMask;
	public $name = '.*';
	public $separator = '/';
	public $filterFiles;
	public $filterFolders;
	public $skipRoot = false;
	public $maxDepth = 0; /* 0 = unlimited */

	public function __construct ($filterFolders, $filterFiles) {
		$this->filterFolders = $filterFolders;
		$this->filterFiles = $filterFiles;
	}

	private function getContent ($dir) {
	   $result = array(); 

	   $cdir = scandir($dir); 
	   foreach ($cdir as $key => $value) 
	   { 
	      if (!in_array($value,array(".",".."))) 
	      { 
	         if (is_dir($dir.$this->separator.$value)) 
	         { 
	            $result[$dir.$this->separator.$value] = self::getContent($dir.$this->separator.$value); 
	         }
	         else 
	         { 
	            $result[] = $dir.$this->separator.$value; 
	         } 
	      } 
	   } 
	   
	   return $result; 
	}

	private function filterFolders ($array, $depth) {
		$depth++;

		foreach ($array as $key => $value) {
			if (is_array($array[$key]))	{
				if ($this->pathStrip)
					$_key = str_replace($this->path.'/', '', $key);				
				$this->folders[] = $_key;

				if ($this->maxDepth === 0 || $depth <= $this->maxDepth) {
					self::filterFolders($array[$key], $depth);
				}
			}						
		}
	}

	private function filterFiles ($array, $file, $depth) {
		foreach ($array as $key => $value) {
			if (is_array($array[$key]))	{
				if ($this->maxDepth === 0 || $depth <= $this->maxDepth) {
					self::filterFiles($array[$key], $file);
				}
			} else {
				if (@preg_match("/".$file."/i", $value)) {
					if ($this->pathStrip)
						$value = str_replace($this->path.'/', '', $value);
					if ($this->extensionStrip) {
						$ext = pathinfo($value, PATHINFO_EXTENSION);
						$value = str_replace('.'.$ext, '', $value);
					}
					$this->files[] = $value;				
				}
			}						
		}

	}	

	public function output () {
		if (substr($this->path, -1) == '/') {
			$this->path = substr($this->path, 0, -1);
		}

		$this->content = self::getContent($this->path);
		if ($this->filterFolders) {
			self::filterFolders($this->content, 1);
			if (!$this->skipRoot) {
				array_unshift($this->folders, $this->path);
			}
		}

		if ($this->filterFiles) {
			self::filterFiles($this->content, $this->fileMask, 1);
		}

		return array_merge($this->files, $this->folders);
	}

}

?>