<?php
/** * @author         Ni Irrty <niirrty+code@gmail.com>
 * @copyright  (c) 2017, Ni Irrty
 * @since          2017-11-21
 */


declare( strict_types = 1 );


namespace Niirrty\Holiday;


use Niirrty\IO\File;
use Niirrty\IO\FileFormatException;
use Niirrty\IO\FileNotFoundException;
use Niirrty\IO\Folder;


/**
 * A country depending holiday definition collection factory
 */
class CountryDefinitionsFactory
{


   // <editor-fold desc="= = =   P U B L I C   S T A T I C   M E T H O D S   = = = = = = = = = = = = = = = = = =">

   /**
    * Gets the holiday definitions of defined country
    *
    * @param  string      $countryId         The 2 char ISO ISO-3166-1 country ID in lower case (e.g.: 'de', 'uk', etc.)
    * @param  string|null $definitionsFolder Optional definitions folder if its different from the build in data folder
    * @return \Niirrty\Holiday\DefinitionCollection
    * @throws \Niirrty\IO\FileNotFoundException If the required holiday definitions file not exist
    * @throws \Niirrty\IO\FileFormatException   If the country depending holidays file is not valid PHP or not a valid collection
    */
   public static function Create( string $countryId, ?string $definitionsFolder = null ) : DefinitionCollection
   {

      if ( empty( $definitionsFolder ) )
      {
         $definitionsFolder = \dirname( __DIR__ ) . '/data';
      }
      else
      {
         $definitionsFolder = \rtrim( $definitionsFolder, '/\\' );
      }

      $file = $definitionsFolder . \DIRECTORY_SEPARATOR . $countryId . '.php';

      if ( ! \file_exists( $file ) )
      {
         throw new FileNotFoundException( $file, 'Can not get holidays for country "' . $countryId . '".' );
      }

      /** @noinspection PhpIncludeInspection */
      try { $collection = include $file; }
      catch ( \Throwable $ex )
      {
         throw new FileFormatException(
            $file,
            'Invalid country "' . $countryId . '" holiday definitions file. Include fails!',
            256,
            $ex
         );
      }

      if ( ! ( $collection instanceof DefinitionCollection ) )
      {
         throw new FileFormatException(
            $file,
            'Invalid country "' . $countryId . '" holiday definitions file. It not defines a "DefinitionCollection"!'
         );
      }

      return $collection;

   }

   /**
    * Gets the 2 char ISO ISO-3166-1 country IDs of the currently supported countries with known holidays
    *
    * @return array
    */
   public static function GetSupportedCountries() : array
   {

      $countryIDs = [];

      foreach ( Folder::ListFilteredFiles( \dirname( __DIR__ ) . '/data', '~[a-z]{2}\.php$~', false ) as $countryFile )
      {
         $lowerCID = \strtolower( File::GetNameWithoutExtension( $countryFile ) );
         if ( 2 === \strlen( $lowerCID ) )
         {
            $countryIDs[] = $lowerCID;
         }
      }

      $countryIDs = \array_unique( $countryIDs );
      \sort( $countryIDs );

      return $countryIDs;

   }

   // </editor-fold>


}

