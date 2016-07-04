<?php

namespace Data\Map;

use Data\Mission;
use Data\MissionQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'cj__missions' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class MissionTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Data.Map.MissionTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'cj__missions';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Data\\Mission';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Data.Mission';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 10;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 10;

    /**
     * the column name for the ID field
     */
    const COL_ID = 'cj__missions.ID';

    /**
     * the column name for the type field
     */
    const COL_TYPE = 'cj__missions.type';

    /**
     * the column name for the date field
     */
    const COL_DATE = 'cj__missions.date';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'cj__missions.name';

    /**
     * the column name for the start field
     */
    const COL_START = 'cj__missions.start';

    /**
     * the column name for the arrival field
     */
    const COL_ARRIVAL = 'cj__missions.arrival';

    /**
     * the column name for the end field
     */
    const COL_END = 'cj__missions.end';

    /**
     * the column name for the code field
     */
    const COL_CODE = 'cj__missions.code';

    /**
     * the column name for the confirmed field
     */
    const COL_CONFIRMED = 'cj__missions.confirmed';

    /**
     * the column name for the user_id field
     */
    const COL_USER_ID = 'cj__missions.user_id';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'Type', 'Date', 'Name', 'Start', 'Arrival', 'End', 'Code', 'Confirmed', 'UserId', ),
        self::TYPE_CAMELNAME     => array('id', 'type', 'date', 'name', 'start', 'arrival', 'end', 'code', 'confirmed', 'userId', ),
        self::TYPE_COLNAME       => array(MissionTableMap::COL_ID, MissionTableMap::COL_TYPE, MissionTableMap::COL_DATE, MissionTableMap::COL_NAME, MissionTableMap::COL_START, MissionTableMap::COL_ARRIVAL, MissionTableMap::COL_END, MissionTableMap::COL_CODE, MissionTableMap::COL_CONFIRMED, MissionTableMap::COL_USER_ID, ),
        self::TYPE_FIELDNAME     => array('ID', 'type', 'date', 'name', 'start', 'arrival', 'end', 'code', 'confirmed', 'user_id', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Type' => 1, 'Date' => 2, 'Name' => 3, 'Start' => 4, 'Arrival' => 5, 'End' => 6, 'Code' => 7, 'Confirmed' => 8, 'UserId' => 9, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'type' => 1, 'date' => 2, 'name' => 3, 'start' => 4, 'arrival' => 5, 'end' => 6, 'code' => 7, 'confirmed' => 8, 'userId' => 9, ),
        self::TYPE_COLNAME       => array(MissionTableMap::COL_ID => 0, MissionTableMap::COL_TYPE => 1, MissionTableMap::COL_DATE => 2, MissionTableMap::COL_NAME => 3, MissionTableMap::COL_START => 4, MissionTableMap::COL_ARRIVAL => 5, MissionTableMap::COL_END => 6, MissionTableMap::COL_CODE => 7, MissionTableMap::COL_CONFIRMED => 8, MissionTableMap::COL_USER_ID => 9, ),
        self::TYPE_FIELDNAME     => array('ID' => 0, 'type' => 1, 'date' => 2, 'name' => 3, 'start' => 4, 'arrival' => 5, 'end' => 6, 'code' => 7, 'confirmed' => 8, 'user_id' => 9, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('cj__missions');
        $this->setPhpName('Mission');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Data\\Mission');
        $this->setPackage('Data');
        $this->setUseIdGenerator(false);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'CHAR', true, 64, null);
        $this->addColumn('type', 'Type', 'VARCHAR', true, 250, null);
        $this->addColumn('date', 'Date', 'DATE', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 250, null);
        $this->addColumn('start', 'Start', 'TIME', true, null, null);
        $this->addColumn('arrival', 'Arrival', 'VARCHAR', false, 250, null);
        $this->addColumn('end', 'End', 'TIME', true, null, null);
        $this->addColumn('code', 'Code', 'FLOAT', false, null, null);
        $this->addColumn('confirmed', 'Confirmed', 'BOOLEAN', true, 1, false);
        $this->addForeignKey('user_id', 'UserId', 'INTEGER', 'cj__users', 'ID', true, null, 0);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('User', '\\Data\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':ID',
  ),
), null, null, null, false);
    } // buildRelations()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (string) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? MissionTableMap::CLASS_DEFAULT : MissionTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (Mission object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = MissionTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = MissionTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + MissionTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = MissionTableMap::OM_CLASS;
            /** @var Mission $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            MissionTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = MissionTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = MissionTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Mission $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                MissionTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(MissionTableMap::COL_ID);
            $criteria->addSelectColumn(MissionTableMap::COL_TYPE);
            $criteria->addSelectColumn(MissionTableMap::COL_DATE);
            $criteria->addSelectColumn(MissionTableMap::COL_NAME);
            $criteria->addSelectColumn(MissionTableMap::COL_START);
            $criteria->addSelectColumn(MissionTableMap::COL_ARRIVAL);
            $criteria->addSelectColumn(MissionTableMap::COL_END);
            $criteria->addSelectColumn(MissionTableMap::COL_CODE);
            $criteria->addSelectColumn(MissionTableMap::COL_CONFIRMED);
            $criteria->addSelectColumn(MissionTableMap::COL_USER_ID);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.type');
            $criteria->addSelectColumn($alias . '.date');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.start');
            $criteria->addSelectColumn($alias . '.arrival');
            $criteria->addSelectColumn($alias . '.end');
            $criteria->addSelectColumn($alias . '.code');
            $criteria->addSelectColumn($alias . '.confirmed');
            $criteria->addSelectColumn($alias . '.user_id');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(MissionTableMap::DATABASE_NAME)->getTable(MissionTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(MissionTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(MissionTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new MissionTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Mission or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Mission object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MissionTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Data\Mission) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(MissionTableMap::DATABASE_NAME);
            $criteria->add(MissionTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = MissionQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            MissionTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                MissionTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the cj__missions table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return MissionQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Mission or Criteria object.
     *
     * @param mixed               $criteria Criteria or Mission object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MissionTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Mission object
        }


        // Set the correct dbName
        $query = MissionQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // MissionTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
MissionTableMap::buildTableMap();
