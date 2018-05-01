<?php
/**
 * @author         Ni Irrty <niirrty+code@gmail.com>
 * @copyright  (c) 2017, Ni Irrty
 * @since          2017-11-21
 */


declare( strict_types = 1 );


namespace Niirrty\Holiday;


use Niirrty\Date\DateTime;


/**
 * Class HolidayCollection.
 */
class HolidayCollection implements \ArrayAccess, \IteratorAggregate, \Countable
{


   // <editor-fold desc="= = =   P R I V A T E   F I E L D S   = = = = = = = = = = = = = = = = = = = = = = = = =">

   /**
    * The country name (e.g. 'Deutschland' or 'United Kingdom')
    *
    * @type string
    */
   private $_countryName;

   /**
    * The 2 char ISO country ID (e.g.: 'de', 'fr')
    *
    * @type string
    */
   private $_countryId;

   /**
    * All Holiday records.
    *
    * @type Holiday[] Array
    */
   private $_data;

   /**
    * The year where the holidays are valid for
    *
    * @type int
    */
   private $_year;

   /**
    * A numeric indicated array that defines the names of all regions for current country.
    *
    * It makes sense to define the region names with the default language. :-)
    *
    * @type array
    */
   private $_regions;

   // </editor-fold>


   // <editor-fold desc="= = =   P U B L I C   C O N S T R U C T O R   = = = = = = = = = = = = = = = = = = = = =">

   /**
    * HolidayCollection constructor.
    *
    * @param  int    $year          The year where the holidays are valid for
    * @param  string $countryName  The country name (e.g. 'Deutschland' or 'United Kingdom')
    * @param  string $countryId    The 2 char ISO country ID (e.g.: 'de', 'fr')
    */
   public function __construct( int $year, string $countryName, string $countryId )
   {

      $this->_year            = $year;
      $this->_countryName     = $countryName;
      $this->_countryId       = $countryId;
      $this->_data            = [];
      $this->_regions         = [];

   }

   // </editor-fold>


   // <editor-fold desc="= = =   P U B L I C   M E T H O D S   = = = = = = = = = = = = = = = = = = = = = = = = =">


   // <editor-fold desc="= = =   I M P L E M E N T   ' I t e r a t o r A g g r e g a t e '   = = = = = = = = = =">

   /**
    * Retrieve an external iterator
    *
    * @link  http://php.net/manual/en/iteratoraggregate.getiterator.php
    * @return \ArrayIterator An instance of an object implementing <b>Iterator</b> or <b>Traversable</b>
    */
   public function getIterator()
   {

      /** @noinspection PhpIncompatibleReturnTypeInspection */
      return new \ArrayIterator( $this->_data );

   }

   // </editor-fold>


   // <editor-fold desc="= = =   I M P L E M E N T   ' A r r a y A c c e s s '   = = = = = = = = = = = = = = = =">

   /**
    * Whether a offset exists.
    *
    * @param  int $offset An offset to check for.
    * @return boolean true on success or false on failure. The return value will be casted to boolean if non-boolean
    *                 was returned.
    * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
    */
   public function offsetExists( $offset )
   {

      return isset( $this->_data[ $offset ] );

   }

   /**
    * Offset to retrieve.
    *
    * @param  int $offset The offset to retrieve.
    * @return Holiday
    * @link   http://php.net/manual/en/arrayaccess.offsetget.php
    */
   public function offsetGet( $offset )
   {

      return $this->_data[ $offset ];

   }

   /**
    * Offset to set.
    *
    * @param  string|null              $offset The offset to assign the value to.
    * @param  \Niirrty\Holiday\Holiday $value  The value to set.
    * @throws \Niirrty\Holiday\Exception
    * @link   http://php.net/manual/en/arrayaccess.offsetset.php
    */
   public function offsetSet( $offset, $value )
   {

      if ( ! ( $value instanceof Holiday ) )
      {
         throw new Exception( 'Can not set an holiday if it is not an Holiday instance!' );
      }

      if ( \is_null( $offset ) )
      {
         $this->_data[ $value->getIdentifier() ] = $value;
      }
      else
      {
         $this->_data[ $offset ] = $value;
      }

   }

   /**
    * Offset to unset.
    *
    * @param int $offset The offset to unset.
    * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
    */
   public function offsetUnset( $offset )
   {

      unset( $this->_data[ $offset ] );

   }

   // </editor-fold>


   // <editor-fold desc="= = =   I M P L E M E N T   ' C o u n t a b l e '   = = = = = = = = = = = = = = = = = =">

   /**
    * Count elements of an object.
    *
    * @return int The custom count as an integer. The return value is cast to an integer.
    * @link   http://php.net/manual/en/countable.count.php
    */
   public function count()
   {

      return \count( $this->_data );

   }

   // </editor-fold>


   // <editor-fold desc="= = =   G E T T E R S   = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =">

   /**
    * Gets a numeric indicated array that defines the names of all regions/provinces/states for current country.
    *
    * @return array
    */
   public function getRegions() : array
   {

      return $this->_regions;

   }

   /**
    * Gets the 2 char ISO country ID (e.g.: 'de', 'fr')
    *
    * @return string
    */
   public function getCountryId() : string
   {

      return $this->_countryId;

   }

   /**
    * Gets the country name (e.g. 'Deutschland' or 'United Kingdom')
    *
    * @return string
    */
   public function getCountryName() : string
   {

      return $this->_countryName;

   }

   /**
    * Get the names of all an registered global callbacks.
    *
    * @return int
    */
   public function getYear() : int
   {

      return $this->_year;

   }

   /**
    * Gets an array with all valid holidays for defined year.
    *
    * @return \Niirrty\Holiday\Holiday[]
    */
   public function getHolidays() : array
   {

      return $this->_data;

   }

   /**
    * Gets if an region with the defined index or name exists.
    *
    * @param  string|int $region The name or index of the required region
    * @return bool
    */
   public function hasRegion( $region ) : bool
   {

      if ( \is_int( $region ) )
      {
         return isset( $this->_regions[ $region ] );
      }

      return ( false !== \array_search( $region, $this->_regions ) );

   }

   /**
    * Gets if the country has registered some known regions.
    *
    * @return bool
    */
   public function hasRegions() : bool
   {

      return \count( $this->_regions ) > 0;

   }

   /**
    * Gets if the Holiday with the defined identifier is defined.
    *
    * @param  string $identifier The Unique holiday identifier
    * @return bool
    */
   public function has( string $identifier ) : bool
   {

      return isset( $this->_data[ $identifier ] );

   }

   /**
    * Gets the Holiday with the defined identifier, or NULL if no holiday for identifier is defined.
    *
    * @param  string $identifier The Unique holiday identifier
    * @return \Niirrty\Holiday\Holiday|null
    */
   public function get( string $identifier ) : ?Holiday
   {

      if ( ! $this->has( $identifier ) )
      {
         return null;
      }

      return $this->_data[ $identifier ];

   }

   /**
    * Gets the identifiers or all current defined holidays.
    *
    * @return array
    */
   public function getIdentifiers() : array
   {

      return \array_keys( $this->_data );

   }

   // </editor-fold>


   // <editor-fold desc="= = =   S E T T E R S   = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =">

   /**
    * Sets a numeric indicated array that defines the names of all regions/provinces/states for current country.
    *
    * The associated indexes are used later for point to the regions
    *
    * @param  array $regions
    * @return \Niirrty\Holiday\HolidayCollection
    */
   public function setRegions( array $regions ) : HolidayCollection
   {

      $this->_regions = $regions;

      return $this;

   }

   /**
    * Add an holiday to the collection.
    *
    * @param  \Niirrty\Holiday\Holiday $holiday
    * @return \Niirrty\Holiday\HolidayCollection
    */
   public function add( Holiday $holiday ) : HolidayCollection
   {

      $this->_data[ $holiday->getIdentifier() ] = $holiday;

      return $this;

   }

   /**
    * Add one or more holidays to the collection.
    *
    * @param  \Niirrty\Holiday\Holiday[] ...$holidays
    * @return \Niirrty\Holiday\HolidayCollection
    */
   public function addRange( Holiday ...$holidays ) : HolidayCollection
   {

      foreach ( $holidays as $holiday )
      {
         $this->_data[ $holiday->getIdentifier() ] = $holiday;
      }

      return $this;

   }

   // </editor-fold>


   // <editor-fold desc="= = =   O T H E R   P U B L I C   M E T H O D S   = = = = = = = = = = = = = = = = = = =">

   /**
    * Extract the names of all regions with the defined indexes.
    *
    * @param  array $regionIndexes The indexes of the required regions
    * @return array
    */
   public function extractRegionNames( array $regionIndexes ) : array
   {

      $names = [];

      if ( \count( $regionIndexes ) < 1 || $regionIndexes[ 0 ] === -1 )
      {
         return $this->_regions;
      }

      foreach ( $regionIndexes as $regionIndex )
      {
         if ( ! isset( $this->_regions[ $regionIndex ] ) )
         {
            continue;
         }
         $names[ $regionIndex ] = $this->_regions[ $regionIndex ];
      }

      return $names;

   }

   /**
    * Gets the index of an region with the defined name, or FALSE if it not exists.
    *
    * @param  string $region The name of the required region
    * @return int|FALSE
    */
   public function indexOfRegion( string $region )
   {

      return \array_search( $region, $this->_regions );

   }

   /**
    * Gets if the defined date is a known holiday for instance year holidays.
    *
    * @param mixed       $date
    * @param string|null $foundIdentifier Return the holiday identifier if the method return true
    * @return bool
    */
   public function containsDate( $date, string &$foundIdentifier = null ) : bool
   {

      if ( false === ( $dt = DateTime::Parse( $date ) ) )
      {
         return false;
      }

      $dateString = '' . $this->_year . $dt->format( '-m-d' );

      foreach ( $this->_data as $holiday )
      {
         if ( $dateString === $holiday->getDate()->format( 'Y-m-d' ) )
         {
            $foundIdentifier = $holiday->getIdentifier();
            return true;
         }
      }

      return false;

   }

   /**
    * Gets if the defined day and month is a known holiday for instance year holidays.
    *
    * @param int         $month
    * @param int         $day
    * @param string|null $foundIdentifier Return the holiday identifier if the method return true
    * @return bool
    */
   public function containsDay( int $month, int $day, string &$foundIdentifier = null ) : bool
   {

      $dt = DateTime::Create( $this->_year, $month, $day );

      $dateString = $dt->format( 'Y-m-d' );

      foreach ( $this->_data as $holiday )
      {
         if ( $dateString === $holiday->getDate()->format( 'Y-m-d' ) )
         {
            $foundIdentifier = $holiday->getIdentifier();
            return true;
         }
      }

      return false;

   }


   public function startsAt( int $month = 1, int $day = 1 ) : HolidayCollection
   {

      $result = ( new HolidayCollection( $this->_year, $this->_countryName, $this->_countryId ) )
         ->setRegions( $this->_regions );

      $minDate = DateTime::Create( $this->_year, $month, $day );

      foreach ( $this->_data as $identifier => $holiday )
      {
         if ( $holiday->getDate() < $minDate )
         {
            continue;
         }
         $result->_data[ $identifier ] = $holiday;
      }

      return $result;

   }

   // </editor-fold>


   // </editor-fold>


   // <editor-fold desc="= = =   P U B L I C   S T A T I C   M E T H O D S   = = = = = = = = = = = = = = = = = =">

   /**
    * Creates a new HolidayCollection instance and returns it.
    *
    * @param  int    $year          The year where the holidays are valid for
    * @param  string $countryName  The country name (e.g. 'Deutschland' or 'United Kingdom')
    * @param  string $countryId    The 2 char ISO country ID (e.g.: 'de', 'fr')
    * @return \Niirrty\Holiday\HolidayCollection
    */
   public static function Create( int $year, string $countryName, string $countryId ) : HolidayCollection
   {

      return new self( $year, $countryName, $countryId );

   }

   // </editor-fold>


}

