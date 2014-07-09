<?php
namespace Terranet;

class Paginator extends \Zend_Paginator
{
    /**
     * Num of pages in range
     * @var integer
     */
	static protected $_range   = 10;


	static public function setOptions($options)
    {
		foreach($options as $key=>$value) {
			switch ($key) {
				case 'pageRange' :
					self::$_range = (int) $value;
					break;

				default :
					throw new Exception('Invalid option provided');
					break;
			}
		}
	}

    /**
      * init paginator
      *
      * @param mixed array|string|Zend_Db_Select|Zend_Db_Table_Select $query
      * @param int $page
      * @param int $perPage
      * @param string $adapter
      */
    public function __construct($query, $page = 1, $perPage = 20, $adapterClass = null)
    {
        if (null === $adapterClass) {
            if (is_array($query)) {
                $adapter = 'Array';
            } else {
                $adapter = 'DbTableSelect';
            }
            $adapterClass = "\Zend_Paginator_Adapter_{$adapter}";
        }

    	$adapter = new $adapterClass($query);

        parent::__construct($adapter);

        $this->setCurrentPageNumber($page)
             ->setItemCountPerPage($perPage)
			 ->setPageRange(self::$_range);
    }
}
