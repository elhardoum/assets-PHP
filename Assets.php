<?php

namespace Assets;

abstract class Assets
{
    private $scripts, $active, $handlers, $id, $src;
    public $dequeue_children_on_missing = true;
    public $check_parent_dependency = true;

    public function __construct()
    {
        $this->scripts = (array) $this->scripts;
        $this->handlers = array();
        $this->active = (array) $this->active;
    }

    public function handler($id, $data)
    {
        $this->handlers[$id] = $data;
        $this->id = $id;
        $this->src = $data;

        return $this;
    }

    public function isRegistered($id)
    {
        return !empty($this->scripts[$id]);
    }

    public function src($id, $data)
    {
        if ( !$this->isRegistered($id) )
            return $this;

        $this->scripts[$id]['src'] = $data;
        $this->id = $id;
        $this->src = $data;

        return $this;
    }

    public function add($id=null, $src=null, $after='')
    {
        if ( !$id && $this->id ) {
            $id = $this->id;
        }

        if ( !$src && $this->src ) {
            $src = $this->src;
        }

        if ( !$src || !$id )
            return $this;

        $script = array(
            'id' => $id,
            'src' => $src,
        );

        if ( strip_tags($src) == $src && file_exists(ROOT_DIR . $src) ) {
            $script['path'] = ROOT_DIR . $src;
        }

        if ( $after ) {
            if ( !isset($this->scripts[$after]) && isset($this->handlers[$after]) ) {
                $this->add($after, $this->handlers[$after]);
            }
            $script['after'] = $after;
        }

        $this->scripts[$id] = $script;
        $this->id = $id;
        $this->active[$id] = false;

        return $this;
    }

    public function enqueue($id=null)
    {
        if ( !$id && $this->id )
            $id = $this->id;

        if ( !$id )
            return $this;

        $this->active[$id] = true;

        if ( !empty($this->scripts[$id]['after']) && !$this->isActive($this->scripts[$id]['after']) ) {
            $this->enqueue($this->scripts[$id]['after']);
        }

        return $this;
    }

    public function dequeue($id=null)
    {
        if ( !$id && $this->id )
            $id = $this->id;

        if ( !$id )
            return $this;

        $this->active[$id] = false;

        return $this;
    }

    public function remove($id, $andDeps=null)
    {
        if ( isset($this->scripts[$id]) ) {
            unset($this->scripts[$id]);

            if ( $andDeps ) {
                foreach ( $this->scripts as $i=>$script ) {
                    switch ( true ) {
                        case isset($script['after']) && $id == $script['after']:
                            unset($this->scripts[$i]);
                            break;
                    }
                }
            }
        }

        return $this;
    }

    private function sort()
    {
        if ( !$this->scripts )
            return $this;

        foreach ( $this->scripts as $i=>$script ) {
            $after = isset($script['after']) ? $script['after'] : null;
            if ( $after ) {
                $parentIndex = array_search($after, array_keys($this->scripts));
                $childIndex = array_search($script['id'], array_keys($this->scripts));

                if ( $parentIndex > $childIndex ) {
                    unset($this->scripts[$i]);
                    $this->scripts = $this->insert($this->scripts, $parentIndex, array($script['id'] => $script));
                }
            }
        }

        return $this;
    }

    private function insert($array, $position, $insertArray)
    {
        $ret = [];

        if ($position == count($array)) {
            $ret = $array + $insertArray;
        } else {
            $i = 0;
            foreach ($array as $key => $value) {
                if ($position == $i++) {
                    $ret += $insertArray;
                }

                $ret[$key] = $value;
            }
        }

        return $ret;
    }

    private function cleanIndex()
    {
        $real = 0;
        foreach ( $this->scripts as $i=>$script ) {
            if ( is_numeric($i) ) {
                $this->scripts = $this->insert($this->scripts, $real, array($script['id'] => $script));
                unset($this->scripts[$i]);
            }

            $real++;
        }
    }

    public function print()
    {
        $this->sort();

        if ( empty($this->scripts) )
            return;

        foreach ( $this->scripts as $script ) {            
            $this->item($script);
        }
    }

    public function isActive($id)
    {
        return isset($this->active[$id]) && $this->active[$id];
    }

    public function dequeueChildren($dep)
    {
        if ( $this->scripts ) {
            foreach ( $this->scripts as $i=>$script ) {
                $id = $script['id'];

                if ( !$this->isActive($id) )
                    continue;

                if ( !empty($script['after']) && $script['after'] == $dep ) {
                    $this->dequeue($id);
                }
            }
        }
    }

    private function item($script)
    {
        $id = $script['id'];

        if ( !$this->isActive($id) ) {
            return;
        }

        if ( $this->check_parent_dependency ) {
            if ( !empty($script['after']) && !$this->isActive($script['after']) ) {
                return $this->dequeue_children_on_missing ? $this->dequeueChildren($script['after']) : null;
            }
        }

        if ( $this->isInline($script['src']) ) {
            print $script['src'] . PHP_EOL;
        } else if ( !empty($script['path']) ) {
            if ( !defined('DEV') || !DEV ) {            
                $this->printItemWithPath($script);
            } else {
                $this->printItemWithSrc($script);
            }
        } else {
            $this->printItemWithSrc($script);
        }
    }

    abstract function isInline($data);
    abstract function printItemWithPath($script);
    abstract function printItemWithSrc($script);
}