<?php
namespace Terranet\Paginator\Adapter;


class MongoCursor implements \Zend_Paginator_Adapter_Interface
{
    protected $_cursor = null;

    /**
     * Item count
     *
     * @var integer
     */
    protected $_count = null;

    /**
     * Constructor.
     *
     * @param array $array Array to paginate
     */
    public function __construct($cursor)
    {
        $this->setCursor($cursor);

        $this->_count  = $this->getCursor()->count();
    }

    public function setCursor($cursor)
    {
        $this->_cursor = $cursor;
        return $this;
    }

    public function getCursor()
    {
        return $this->_cursor;
    }

    /**
     * Returns an array of items for a page.
     *
     * @param  integer $offset Page offset
     * @param  integer $itemCountPerPage Number of items per page
     * @return array
     */
    public function getItems($offset, $itemCountPerPage)
    {
    	$items = $this->getCursor()
                      ->skip($offset)
                      ->limit($itemCountPerPage);

        $items = $this->_cursorToArray($items);

        return $items;
    }

    /**
     * Returns the total number of rows in the array.
     *
     * @return integer
     */
    public function count()
    {
        return $this->_count;
    }

    public function _cursorToArray($results)
    {
    	$out = array();
        $i = 0;
        foreach ($results as $row) {
            $out[$i] = $this->_rowToArray($row);
            $i++;
        }

        return $out;
    }

    protected function _rowToArray($row)
    {
        $tmp = array();
        foreach ($row as $key => $value) {
            if (is_object($value)) {
                if ($value instanceof MongoDate) {
                    $tmp[$key] = date('Y-m-d H:i:s', $value->sec);
                } else if (method_exists($value, "__toString")) {
                    $tmp[$key] = $value->__toString();
                } else {
                    $tmp[$key] = $value;
                }
            } else {
                $tmp[$key] = $value;
            }
        }
        return $tmp;
    }
}
