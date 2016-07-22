<?php

namespace Data\Base;

use \Exception;
use \PDO;
use Data\Mission as ChildMission;
use Data\MissionQuery as ChildMissionQuery;
use Data\Map\MissionTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'cj__missions' table.
 *
 *
 *
 * @method     ChildMissionQuery orderById($order = Criteria::ASC) Order by the ID column
 * @method     ChildMissionQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method     ChildMissionQuery orderByDate($order = Criteria::ASC) Order by the date column
 * @method     ChildMissionQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildMissionQuery orderByStart($order = Criteria::ASC) Order by the start column
 * @method     ChildMissionQuery orderByArrival($order = Criteria::ASC) Order by the arrival column
 * @method     ChildMissionQuery orderByEnd($order = Criteria::ASC) Order by the end column
 * @method     ChildMissionQuery orderByCode($order = Criteria::ASC) Order by the code column
 * @method     ChildMissionQuery orderByTrain($order = Criteria::ASC) Order by the train column
 * @method     ChildMissionQuery orderByConfirmed($order = Criteria::ASC) Order by the confirmed column
 * @method     ChildMissionQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 *
 * @method     ChildMissionQuery groupById() Group by the ID column
 * @method     ChildMissionQuery groupByType() Group by the type column
 * @method     ChildMissionQuery groupByDate() Group by the date column
 * @method     ChildMissionQuery groupByName() Group by the name column
 * @method     ChildMissionQuery groupByStart() Group by the start column
 * @method     ChildMissionQuery groupByArrival() Group by the arrival column
 * @method     ChildMissionQuery groupByEnd() Group by the end column
 * @method     ChildMissionQuery groupByCode() Group by the code column
 * @method     ChildMissionQuery groupByTrain() Group by the train column
 * @method     ChildMissionQuery groupByConfirmed() Group by the confirmed column
 * @method     ChildMissionQuery groupByUserId() Group by the user_id column
 *
 * @method     ChildMissionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMissionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMissionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMissionQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildMissionQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildMissionQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildMissionQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildMissionQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildMissionQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildMissionQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildMissionQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildMissionQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildMissionQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     \Data\UserQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildMission findOne(ConnectionInterface $con = null) Return the first ChildMission matching the query
 * @method     ChildMission findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMission matching the query, or a new ChildMission object populated from the query conditions when no match is found
 *
 * @method     ChildMission findOneById(string $ID) Return the first ChildMission filtered by the ID column
 * @method     ChildMission findOneByType(string $type) Return the first ChildMission filtered by the type column
 * @method     ChildMission findOneByDate(string $date) Return the first ChildMission filtered by the date column
 * @method     ChildMission findOneByName(string $name) Return the first ChildMission filtered by the name column
 * @method     ChildMission findOneByStart(string $start) Return the first ChildMission filtered by the start column
 * @method     ChildMission findOneByArrival(string $arrival) Return the first ChildMission filtered by the arrival column
 * @method     ChildMission findOneByEnd(string $end) Return the first ChildMission filtered by the end column
 * @method     ChildMission findOneByCode(double $code) Return the first ChildMission filtered by the code column
 * @method     ChildMission findOneByTrain(int $train) Return the first ChildMission filtered by the train column
 * @method     ChildMission findOneByConfirmed(boolean $confirmed) Return the first ChildMission filtered by the confirmed column
 * @method     ChildMission findOneByUserId(int $user_id) Return the first ChildMission filtered by the user_id column *

 * @method     ChildMission requirePk($key, ConnectionInterface $con = null) Return the ChildMission by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMission requireOne(ConnectionInterface $con = null) Return the first ChildMission matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMission requireOneById(string $ID) Return the first ChildMission filtered by the ID column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMission requireOneByType(string $type) Return the first ChildMission filtered by the type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMission requireOneByDate(string $date) Return the first ChildMission filtered by the date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMission requireOneByName(string $name) Return the first ChildMission filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMission requireOneByStart(string $start) Return the first ChildMission filtered by the start column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMission requireOneByArrival(string $arrival) Return the first ChildMission filtered by the arrival column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMission requireOneByEnd(string $end) Return the first ChildMission filtered by the end column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMission requireOneByCode(double $code) Return the first ChildMission filtered by the code column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMission requireOneByTrain(int $train) Return the first ChildMission filtered by the train column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMission requireOneByConfirmed(boolean $confirmed) Return the first ChildMission filtered by the confirmed column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMission requireOneByUserId(int $user_id) Return the first ChildMission filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMission[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildMission objects based on current ModelCriteria
 * @method     ChildMission[]|ObjectCollection findById(string $ID) Return ChildMission objects filtered by the ID column
 * @method     ChildMission[]|ObjectCollection findByType(string $type) Return ChildMission objects filtered by the type column
 * @method     ChildMission[]|ObjectCollection findByDate(string $date) Return ChildMission objects filtered by the date column
 * @method     ChildMission[]|ObjectCollection findByName(string $name) Return ChildMission objects filtered by the name column
 * @method     ChildMission[]|ObjectCollection findByStart(string $start) Return ChildMission objects filtered by the start column
 * @method     ChildMission[]|ObjectCollection findByArrival(string $arrival) Return ChildMission objects filtered by the arrival column
 * @method     ChildMission[]|ObjectCollection findByEnd(string $end) Return ChildMission objects filtered by the end column
 * @method     ChildMission[]|ObjectCollection findByCode(double $code) Return ChildMission objects filtered by the code column
 * @method     ChildMission[]|ObjectCollection findByTrain(int $train) Return ChildMission objects filtered by the train column
 * @method     ChildMission[]|ObjectCollection findByConfirmed(boolean $confirmed) Return ChildMission objects filtered by the confirmed column
 * @method     ChildMission[]|ObjectCollection findByUserId(int $user_id) Return ChildMission objects filtered by the user_id column
 * @method     ChildMission[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class MissionQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Data\Base\MissionQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Data\\Mission', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMissionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMissionQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildMissionQuery) {
            return $criteria;
        }
        $query = new ChildMissionQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildMission|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MissionTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = MissionTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMission A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT ID, type, date, name, start, arrival, end, code, train, confirmed, user_id FROM cj__missions WHERE ID = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_STR);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildMission $obj */
            $obj = new ChildMission();
            $obj->hydrate($row);
            MissionTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildMission|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildMissionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(MissionTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildMissionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(MissionTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the ID column
     *
     * Example usage:
     * <code>
     * $query->filterById('fooValue');   // WHERE ID = 'fooValue'
     * $query->filterById('%fooValue%'); // WHERE ID LIKE '%fooValue%'
     * </code>
     *
     * @param     string $id The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMissionQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($id)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MissionTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the type column
     *
     * Example usage:
     * <code>
     * $query->filterByType('fooValue');   // WHERE type = 'fooValue'
     * $query->filterByType('%fooValue%'); // WHERE type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $type The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMissionQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($type)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MissionTableMap::COL_TYPE, $type, $comparison);
    }

    /**
     * Filter the query on the date column
     *
     * Example usage:
     * <code>
     * $query->filterByDate('2011-03-14'); // WHERE date = '2011-03-14'
     * $query->filterByDate('now'); // WHERE date = '2011-03-14'
     * $query->filterByDate(array('max' => 'yesterday')); // WHERE date > '2011-03-13'
     * </code>
     *
     * @param     mixed $date The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMissionQuery The current query, for fluid interface
     */
    public function filterByDate($date = null, $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(MissionTableMap::COL_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(MissionTableMap::COL_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MissionTableMap::COL_DATE, $date, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMissionQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MissionTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the start column
     *
     * Example usage:
     * <code>
     * $query->filterByStart('2011-03-14'); // WHERE start = '2011-03-14'
     * $query->filterByStart('now'); // WHERE start = '2011-03-14'
     * $query->filterByStart(array('max' => 'yesterday')); // WHERE start > '2011-03-13'
     * </code>
     *
     * @param     mixed $start The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMissionQuery The current query, for fluid interface
     */
    public function filterByStart($start = null, $comparison = null)
    {
        if (is_array($start)) {
            $useMinMax = false;
            if (isset($start['min'])) {
                $this->addUsingAlias(MissionTableMap::COL_START, $start['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($start['max'])) {
                $this->addUsingAlias(MissionTableMap::COL_START, $start['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MissionTableMap::COL_START, $start, $comparison);
    }

    /**
     * Filter the query on the arrival column
     *
     * Example usage:
     * <code>
     * $query->filterByArrival('fooValue');   // WHERE arrival = 'fooValue'
     * $query->filterByArrival('%fooValue%'); // WHERE arrival LIKE '%fooValue%'
     * </code>
     *
     * @param     string $arrival The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMissionQuery The current query, for fluid interface
     */
    public function filterByArrival($arrival = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($arrival)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MissionTableMap::COL_ARRIVAL, $arrival, $comparison);
    }

    /**
     * Filter the query on the end column
     *
     * Example usage:
     * <code>
     * $query->filterByEnd('2011-03-14'); // WHERE end = '2011-03-14'
     * $query->filterByEnd('now'); // WHERE end = '2011-03-14'
     * $query->filterByEnd(array('max' => 'yesterday')); // WHERE end > '2011-03-13'
     * </code>
     *
     * @param     mixed $end The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMissionQuery The current query, for fluid interface
     */
    public function filterByEnd($end = null, $comparison = null)
    {
        if (is_array($end)) {
            $useMinMax = false;
            if (isset($end['min'])) {
                $this->addUsingAlias(MissionTableMap::COL_END, $end['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($end['max'])) {
                $this->addUsingAlias(MissionTableMap::COL_END, $end['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MissionTableMap::COL_END, $end, $comparison);
    }

    /**
     * Filter the query on the code column
     *
     * Example usage:
     * <code>
     * $query->filterByCode(1234); // WHERE code = 1234
     * $query->filterByCode(array(12, 34)); // WHERE code IN (12, 34)
     * $query->filterByCode(array('min' => 12)); // WHERE code > 12
     * </code>
     *
     * @param     mixed $code The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMissionQuery The current query, for fluid interface
     */
    public function filterByCode($code = null, $comparison = null)
    {
        if (is_array($code)) {
            $useMinMax = false;
            if (isset($code['min'])) {
                $this->addUsingAlias(MissionTableMap::COL_CODE, $code['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($code['max'])) {
                $this->addUsingAlias(MissionTableMap::COL_CODE, $code['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MissionTableMap::COL_CODE, $code, $comparison);
    }

    /**
     * Filter the query on the train column
     *
     * Example usage:
     * <code>
     * $query->filterByTrain(1234); // WHERE train = 1234
     * $query->filterByTrain(array(12, 34)); // WHERE train IN (12, 34)
     * $query->filterByTrain(array('min' => 12)); // WHERE train > 12
     * </code>
     *
     * @param     mixed $train The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMissionQuery The current query, for fluid interface
     */
    public function filterByTrain($train = null, $comparison = null)
    {
        if (is_array($train)) {
            $useMinMax = false;
            if (isset($train['min'])) {
                $this->addUsingAlias(MissionTableMap::COL_TRAIN, $train['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($train['max'])) {
                $this->addUsingAlias(MissionTableMap::COL_TRAIN, $train['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MissionTableMap::COL_TRAIN, $train, $comparison);
    }

    /**
     * Filter the query on the confirmed column
     *
     * Example usage:
     * <code>
     * $query->filterByConfirmed(true); // WHERE confirmed = true
     * $query->filterByConfirmed('yes'); // WHERE confirmed = true
     * </code>
     *
     * @param     boolean|string $confirmed The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMissionQuery The current query, for fluid interface
     */
    public function filterByConfirmed($confirmed = null, $comparison = null)
    {
        if (is_string($confirmed)) {
            $confirmed = in_array(strtolower($confirmed), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(MissionTableMap::COL_CONFIRMED, $confirmed, $comparison);
    }

    /**
     * Filter the query on the user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE user_id = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE user_id IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE user_id > 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param     mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMissionQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(MissionTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(MissionTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MissionTableMap::COL_USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query by a related \Data\User object
     *
     * @param \Data\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMissionQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \Data\User) {
            return $this
                ->addUsingAlias(MissionTableMap::COL_USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MissionTableMap::COL_USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \Data\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMissionQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Data\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\Data\UserQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMission $mission Object to remove from the list of results
     *
     * @return $this|ChildMissionQuery The current query, for fluid interface
     */
    public function prune($mission = null)
    {
        if ($mission) {
            $this->addUsingAlias(MissionTableMap::COL_ID, $mission->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the cj__missions table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MissionTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MissionTableMap::clearInstancePool();
            MissionTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MissionTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MissionTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            MissionTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MissionTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // MissionQuery
