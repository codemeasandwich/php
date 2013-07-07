<?php
/**
* This is an enum behaviour class that was designed to be inherited into standard class is to allow for more intuitive enum coding
*
* This is a different way of defining enum as compared to php's <a href="http://php.net/manual/en/class.splenum.php">SplEnum</a>. 
*
* @version  16-Apr-2012
* @author Brian Shannon
*
* @package enum
*/
namespace system\enum;

/**
*	@note To help identify classes that cannot be instantiated we prefix them with a single underscore at the beginning
@note In order to help distinguish between variables passed into the function as against variables created inside it. We prefix the start of the variable name with an underscore.
*/
	abstract class _Enumerat
	{
		private $value;
		
		/**
		*	To help in force a standardisation on enum(as distinct from other objects) developer must invoke the enum constant value
		@see _Enumerat::__callStatic(a,b)
		*/
		private function __construct($_value)
		{			
			$this->value = $_value;
		} 
		
		/**
		 * This will convert this object instance into a string representing the enum if you try and use this object like a string
		 * @return string
		 */
		public function __toString()
		{
			return ''.$this();
		}
		
		/**
		* Will check if the input is compatible/valid with this instance of enum
		*
		* @param mixed $_enum you can pass in the enum object or the value of the enum
		* @return boolean
		 */
		
		final public static function check($_enum){
		
			if($_enum instanceof static)
			{	
				return TRUE;	
			}
			else
			{
				return in_array($_enum, self::listValues() , TRUE);
			}
			return FALSE;
		}
		
		/**
		* Will let you know if an enum value exists in the list at the requested index
		*
		* @param int $_num the index you want to check for
		* @throws InvalidArgumentException if the input is not an int
		* @return boolean true if exists false if not
		*/
		public static function checkIndex($_num)
		{
			if( ! is_int($_num))
			{	
				throw new \InvalidArgumentException('input is not an integer');	
			}
			
			$count = count(self::listValues());
			if(0 <= $_num AND $_num < $count)
			{	
				return TRUE; 
			}
			
			return FALSE;
		}
		
		/**
		* Gets the position of this enum in the list as ordered in the source code.
		* @return int
		*/
		public function index()
		{
			return $self::indexOf($this->value);
		}
		
		/**
		* Gets the position of the request enum as ordered in the source code.
		* @param int $_num the index you want to check for
		* @return int
		*/
		public static function indexOf($_enum)
		{
			//if its an object of enum, then pull the value out of it
			if($_enum instanceof static)
			{	$enum = $_enum();	}
			else
			{	$enum = $_enum;		}
			
			$list = self::listValues();
			
			$count = 0;
			foreach($list as $val)
			{
				if($val === $enum)
				{
					return $count;
				}
				$count++;
			}
			return -1;
			
		}
			
		/**
		 * This will return an array of all the predefined constants for this type of enum. The enum name will be the key and the enum's value... well, in value
		 * @return array
		 */
		public static function listValues()
		{
			$oClass = new \ReflectionClass(get_called_class());
			return $oClass->getConstants();
		}
		
		/**
		*	This is invoked when the you want to creat an enum. it will acts like a constructor
		*/
		public static function __callStatic($_name, $_asString = FALSE)
		{
			$class = get_called_class();
			$oClass = new \ReflectionClass($class);
			$value = $oClass->getConstant($_name);
			
			if(NULL  === $value 
			OR FALSE === $value)
			{	trigger_error('invalid enum value',E_USER_ERROR); }
			else
			{	return new $class($value);	}
		}
		
		
	/**
	 * This allows you to quickly and easily access the value of the enum while keeping the enum object intact
	 * @return mixed 
	 */
		public function __invoke()
		{
			return $this->value;
		}
	}
