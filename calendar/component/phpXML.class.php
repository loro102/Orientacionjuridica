<?php

/***********************************************************************************

 phpXML.class.php

 Written by Andy Frey (andy /at/ onesandzeros [dot] biz)
 Copyright (c) 2004, 2005, 2006, 2007, 2008 Andy Frey.
 http://onesandzeros.biz

 License:

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA 

 
 Description:
 
 This is a simple, lightweight XML parsing class that outputs a nice 
 clean array, similar to how Flash builds its XML tree object.


 Version History:

 Version: 1.1.1 (2008-04-16) -- added one litle ? in the UtilString class to properly parse attributes with = in them
									(thank you José!)
 Version: 1.1.0 (2007-03-27) -- rewrote parsing engine (no longer using regexp, speeds it up)
 								rewrote miscellaneous other methods in the class (attribute parsing, for one)
 								deals better with DOCTYPE, ENTITY, ELEMENT, etc.
 Version: 1.0.5 (2006-09-25) -- fixed in_array parameter count problem on line 394 (thanks Andrew!)
 Version: 1.0.4 (2006-08-15) -- fixed minor warning about $head in line 113 (needed to be declared higher up)
 Version: 1.0.3 (2006-02-01) -- fixed CDATA fix (problem with tags inside CDATA tags fixed)
 Version: 1.0.2 (2005-12-15) -- added outputHeader parameter to phpXML->toString()
 Version: 1.0.1 (2005-10-12) -- fixed CDATA regex issue
 Version: 1.0.0 (2005-09-12)


 For full-on descriptions and instructions, go to http://onesandzeros.biz/xml/


***********************************************************************************/

class phpXML extends phpXMLNode {

	var $version;
	var $encoding;
	var $docType;
	var $start;
	var $stop;
	var $parseTime;
	var $storeEntityList;
	var $entityList;
	var $extraEntities;

	function phpXML( $xmlCode = NULL ) {
		// set up the XMLNode poart of this object
		$this->__phpXMLinitialize();
		if( isset( $xmlCode ) ) {
			$this->parseXML( $xmlCode );
		}
	}

	function __phpXMLinitialize() {
		// set up the XMLNode poart of this object
		$this->__initialize();
		$this->name = "rootNode";
		$this->version = "";
		$this->encoding = "";
		$this->docType = array();
		$this->start = 0;
		$this->stop = 0;
		$this->parseTime = 0.0;
		$this->storeEntityList = false;
		$this->entityList = array();
		$this->extraEntities = array();
	}

	function parseXML( $xmlCode ) {
		// start with a fresh XML doc
		$this->__phpXMLinitialize();
		// start the clock
		$this->start = $this->microtime_float();
		// now, first pass through text, remove newlines and carriage returns
		$entityList = array();
		$currentEntity = '';
		$cdata = '';
		for( $i = 0; $i < strlen( $xmlCode ); $i++ ) {
			$c = $xmlCode[$i];
			if( $c == "\n" || $c == "\r" ) {
				$c = " "; // this replaces CR and LF with space
			}
			if( $c == '<' || $c == '>' ) {
				if( strlen( trim( $currentEntity ) ) ) {
					if( $c == '>' ) {
						$currentEntity .= '>';
						$c = '';
					}
					// handle CDATA entities
					if( strlen( $cdata ) || substr( $currentEntity, 0, 9 ) == '<![CDATA[' || substr( $currentEntity, -3 ) == ']]>' ) {
						$cdata .= $currentEntity;
						if( $cdata && substr( $currentEntity, -3 ) == ']]>' ) {
							array_push( $entityList, $cdata );
							$cdata = '';
						}
					} else {
						array_push( $entityList, $currentEntity );
					}
				}
				$currentEntity = '';
			}
			$currentEntity .= $c;
		}
		// for debugging the tag list on the first pass
		if( $this->storeEntityList ) {
			$this->entityList = $entityList;
		}
		if( count( $entityList ) ) {
			$this->__addNodes( $this, $entityList );
		}
		// stop the clock
		$this->stop = $this->microtime_float();
		$this->parseTime = $this->stop - $this->start;
	}

	// this now loads AND parses out the tags (char by char, which is why we don't use the parseXML() method
	function load( $xmlFilename ) {
		// start with a fresh XML doc
		$this->__phpXMLinitialize();
		// start the clock
		$this->start = $this->microtime_float();
		// open the file and parse
		if( !( $f = @fopen( $xmlFilename, 'r' ) ) ) {
			return false;
		} else {
			$entityList = array();
			$currentEntity = '';
			$cdata = '';
			while( false !== ( $c = fgetc( $f ) ) ) {
				if( $c == "\n" || $c == "\r" ) {
					$c = " "; // this replaces CR and LF with space
				}
				if( $c == '<' || $c == '>' ) {
					if( strlen( trim( $currentEntity ) ) ) {
						if( $c == '>' ) {
							$currentEntity .= '>';
							$c = '';
						}
						// handle CDATA entities
						if( strlen( $cdata ) || substr( $currentEntity, 0, 9 ) == '<![CDATA[' || substr( $currentEntity, -3 ) == ']]>' ) {
							$cdata .= $currentEntity;
							if( $cdata && substr( $currentEntity, -3 ) == ']]>' ) {
								array_push( $entityList, $cdata );
								$cdata = '';
							}
						} else {
							array_push( $entityList, $currentEntity );
						}
					}
					$currentEntity = '';
				}
				$currentEntity .= $c;
			}
			fclose( $f );
			// now build node list
			// for debugging the tag list on the first pass
			if( $this->storeEntityList ) {
				$this->entityList = $entityList;
			}
			if( count( $entityList ) ) {
				$this->__addNodes( $this, $entityList );
			}
			// stop the clock
			$this->stop = $this->microtime_float();
			$this->parseTime = $this->stop - $this->start;
		}
		return true;
	}

	// override the toString of the XMLNode class, because we have some extras to return
	// like the XML header and its attributes (version and encoding)
	function toString( $spaces = NULL, $outputHeader = true ) {
		$head = "";
		if( $outputHeader ) {
			if( $this->version != "" || $this->encoding != "" )
				$head = "<?xml" . 
					( $this->version != "" ? " version=\"" . $this->version . "\"" : "" ) . 
					( $this->encoding != "" ? " encoding=\"" . $this->encoding . "\"" : "" ) . 
					" ?>";
		}
		// we need to ignore the root node because it is a placeholder
		// and was not part of the original XML that was parsed
		$s = "";
		if( isset( $spaces ) ) {
			$s .= $head . "\n";
		} else {
			$s .= $head;
		}
		if( $node = $this->firstChild() ) {
			do {
				if( isset( $spaces ) ) {
					$s .= $node->toString( $spaces );
				} else {
					$s .= $node->toString();
				}
			} while( $node = $this->nextChild() );
		}
		return $s;
	}

	function __addNodes( &$parentNode, &$entityList ) {
		// work our way through the tagLine list
		while( count( $entityList ) ) {
			
			// pop off the next tag line
			$entity = array_shift( $entityList );
			$str = new UtilString( $entity );
			
			// if this is a special tag (doctype, entity, element, xml)
			if( $str->startsWith( "<?xml" ) ) {
				// stash xml file info
				$str->value = substr( $entity, 5, strlen( $entity ) - 6 );
				$xmlInfo = $str->toAttributes();
				if( isset( $xmlInfo["version"] ) && $xmlInfo["version"] != "" ) {
					$this->version = $xmlInfo["version"];
				}
				if( isset( $xmlInfo["encoding"] ) && $xmlInfo["encoding"] != "" ) {
					$this->encoding = $xmlInfo["encoding"];
				}
				if( isset( $xmlInfo["standalone"] ) && $xmlInfo["standalone"] != "" ) {
					$this->standalone = $xmlInfo["standalone"];
				}

			} else if( $str->startsWith( "<!DOCTYPE" ) || $str->startsWith( "<!ENTITY" ) || $str->startsWith( "<!ELEMENT" ) ) {
				// stash this thing in the root object
				array_push( $this->extraEntities, $entity );
			
			} else if( $str->startsWith( "<" ) && !$str->startsWith( "<!" ) ) {
				// if this line is a tag but not CDATA or a comment
				// this line is a closing tag or a comment, so just kick out
				if( $str->startsWith( "</" ) ) {
					return;
				}
				$node = new phpXMLNode( $entity );
				if( !$node->isSelfClosed )
					// this is an opening tag, so find children or its value
					$this->__addNodes( $node, $entityList );
				// put this node into the tree
				$parentNode->appendChild( $node );
			
			} else {
				// only if this is NOT a comment
				if( !$str->startsWith( "<!--" ) ) {
					// this line is a node value
					if( $str->startsWith( "<![CDATA[" ) ) {
						// if the programmer used CDATA, tell parent to retain them
						$parentNode->useCDataTags = true;
						// strip off the CDATA tags -- example: <![CDATA[Whipped Wednesday w/ DJ Jeff Sasakura]]
						$entity = substr( $entity, 9, -3 );
					}
					// this line is for the parent node's value property
					$parentNode->setValue( $entity );
				}
			}

		}

	}

	function microtime_float() {
	   list( $usec, $sec ) = explode( " ", microtime() );
	   return ( (float)$usec + (float)$sec );
	}

	function getParseTime() {
		return $this->parseTime;
	}

	function getXMLInfo() {
		return 
			"\nXML Version: " . ( $this->version != "" ? $this->version : "[no XML version set]" ) . 
			"\nEncoding: " . ( $this->encoding != "" ? $this->encoding : "[no encoding set] " );
	}

}

class phpXMLNode {

	var $name;
	var $attributes;
	var $value;
	var $childNodes;
	var $isSelfClosed;
	var $useCDataTags;

	function phpXMLNode( $t = NULL, $a = NULL, $v = NULL, $c = NULL ) {
		$this->__initialize();
		if( $t != NULL ) {
			if( substr( $t, 0, 1 ) == "<" ) {
				// a tagLine is being passed in
				$this->createFromEntityString( $t );
			} else {
				// a string for a name is being passed in
				$this->create( $t, $a, $v, $c );
			}
		}
	}

	function __initialize() {
		$this->name = "";
		$this->attributes = array();
		$this->value = "";
		$this->childNodes = array();
		$this->isSelfClosed = false;
		$this->useCDataTags = false;
	}

	function create( $n, $a = NULL, $v = NULL, $c = NULL ) {
		if( !empty( $n ) ) {
			$this->__initialize();
			$this->name = $n;
			if( isset( $a ) ) {
				if( is_array( $a ) ) {
					$this->attributes = $a;
				} else if( is_string( $a ) ) {
					$str = new UtilString( $a );
					$this->attributes = $str->toAttributes();
				}
			}
			$cpre = "";
			$cpost = "";
			if( isset( $c ) ) {
				$cpre = "<![CDATA[";
				$cpost = "]]>";
			}
			if( isset( $v ) ) {
				$this->setValue( $cpre . $v . $cpost );
			}
		}
	}

	function createFromEntityString( $entity ) {
		if( substr( $entity, -1 ) == ">" ) {
			$entity = substr( $entity, 0, -1 );
		}
		$matches = array();
		preg_match( "/(\/?(\w+))(\s*.*|$)/", $entity, $matches );  // get the tag's name
		$this->name = $matches[2];  // set the node's name
		// if there are attributes, load 'em up
		if( trim( $matches[3] ) != "" ) {
			$str = new UtilString( stripslashes( $matches[3] ) );
			$this->attributes = $str->toAttributes();
		}
		// if this tag is self-closing
		$this->isSelfClosed = substr( $entity, strlen( $entity ) - 1 ) == "/";
	}

	function setName( $n ) {
		if( !empty( $n ) ) {
			$this->name = $n;
		}
	}

	function setValue( $v ) {
		if( !empty( $v ) ) {
			$this->value = $v;
			$this->isSelfClosing = false;
		} else {
			$this->isSelfClosing = true;
		}
	}

	function appendChild( $node ) {
		array_push( $this->childNodes, $node );
	}

	function firstChild() {
		if( count( $this->childNodes ) ) {
			reset( $this->childNodes );
			return current( $this->childNodes );
		} else {
			return false;
		}
	}

	function nextChild() {
		if( count( $this->childNodes ) ) {
			return next( $this->childNodes );
		} else {
			return false;
		}
	}

	function previousChild() {
		if( count( $this->childNodes ) ) {
			return prev( $this->childNodes );
		} else {
			return false;
		}
	}

	function lastChild() {
		if( count( $this->childNodes ) ) {
			return end( $this->childNodes );
		} else {
			return false;
		}
	}

	function hasChildByName( $n ) {
		if( !empty( $n ) ) {
			foreach( $this->childNodes as $node ) {
				if( $node->name == $n ) {
					return true;
				}
			}
		}
		return false;
	}

	function findChildByName( $n ) {
		if( !empty( $n ) ) {
			foreach( $this->childNodes as $node ) {
				if( $node->name == $n ) {
					return $node;
				}
			}
		}
		return false;
	}

	function __getAllByName( $n, &$nodes ) {
		if( !empty( $n ) ) {
			foreach( $this->childNodes as $node ) {
				if( $node->name == $n ) {
					array_push( $nodes, $node );
				} else {
					$node->__getAllByName( $n, $nodes );
				}
			}
			if( count( $nodes ) ) {
				return $nodes;
			}
		}
		return false;
	}

	function getAllByName( $n ) {
		$nodes = array();
		if( !empty( $n ) ) {
			foreach( $this->childNodes as $node ) {
				if( $node->name == $n ) {
					array_push( $nodes, $node );
				} else {
					$node->__getAllByName( $n, $nodes );
				}
			}
			if( count( $nodes ) ) {
				return $nodes;
			}
		}
		return false;
	}

	function getAttribute( $attrName ) {
		foreach( $this->attributes as $name=>$val ) {
			if( $attrName == $name ) {
				return $this->attributes[$name];
			}
		}
		return false;
	}

	function setAttribute( $attrName, $attrVal ) {
		$this->attributes[$attrName] = $attrVal;
	}

	function hasChildren() {
		return count( $this->childNodes ) > 0;
	}

	function childCount() {
		return count( $this->childNodes );
	}

	function toString( $spaces = NULL, $level = NULL ) {
		if( !isset( $level ) ) {
			$level = 0;
		}
		// by default, we output pretty-looking XML code
		$newLine = "\n";
		$indent = str_repeat( "\t", $level );
		if( isset( $spaces ) ) {
			if( is_bool( $spaces ) && !$spaces ) {
				$indent = "";
				$newLine = "";
			} else if( is_numeric( $spaces ) ) {
				$indent = str_repeat( " ", $spaces * $level );
			} else if( is_string( $spaces ) ) {
				$indent = str_repeat( $spaces, $level );
			} else {
				// just in case user passed something else
				$spaces = true;
			}
		} else {
			$spaces = true;
		}
		// open tag
		$s = $indent . "<" . $this->name;
		// if there are atrributes
		if( count( $this->attributes ) ) {
			foreach( $this->attributes as $attrName => $attrVal ) {
				$s .= " " . $attrName . "=\"" . $attrVal . "\"";
			}
		}
		// if there is no text value and no children, self-close this tag
		if( empty( $this->value ) && !$this->hasChildren() ) {
			$s .= "/>" . $newLine;
		} else {
			$s .= ">";
			// if there is a value, tack it on with a close tag and we're finished
			if( !empty( $this->value ) ) {
				// use CDATA if the user requested it
				if( $this->useCDataTags ) {
					$s .= "<![CDATA[";
				}
				// now add the value
				$s .= $this->value;
				// close the CDATA if necessary
				if( $this->useCDataTags ) {
					$s .= "]]>";
				}
				if( $this->hasChildren() ) {
					$s .= $newLine;
				}
			} else {
				$s .= $newLine;
			}
			// if there are children
			if( $this->hasChildren() ) {
				// add the children
				foreach( $this->childNodes as $childNode ) {
					$s .= $childNode->toString( $spaces, $level + 1 );
				}
				$s .= $indent . "</" . $this->name . ">" . $newLine;
			} else {
				if( !empty( $this->value ) ) {
					$s .= "</" . $this->name . ">" . $newLine;
				} else {
					$s .= $indent . "</" . $this->name . ">" . $newLine;
				}
			}
		}
		return $s;
	}

	function write( $f, $append = false ) {
		if( is_writable( $f ) ) {
			if( $fh = fopen( $f, ( $append ? "a" : "w" ) ) ) {
				if( fwrite( $fh, $this->toString( false ) ) !== false ) {
					fclose( $fh );
					return true;
				}
			}
		}
		return false;
	}

}

class UtilString {

	var $value;

	function UtilString( $str ) {
		$this->value = $str;
	}

	function startsWith( $start ) {
		return substr( $this->value, 0, strlen( $start ) ) == $start;
	}

	function toAttributes() {
		$pairs = array();
		preg_match_all( "/\s*(\S+?)\s*=\s*(\".+?\"|\'.+?\')/", $this->value, $pairs ); 
		$attributes = array();
		for( $p = 0; $p < count( $pairs[1] ); $p++ ) {
			$attributes[$pairs[1][$p]] = substr( $pairs[2][$p], 1, -1 );
		}
		return $attributes;
	}

}


?>
