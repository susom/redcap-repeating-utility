<?php
namespace Stanford\EDT;
/** @var \Stanford\EDT\EDT $module */

use \REDCap;

require_once ($module->getModulePath() . "classes/RepeatingForms.php");

class RepeatingFormsExt extends \Stanford\Utilities\RepeatingForms {

    private $pid;
    private $instrument;
    private $record_id;

    function __construct($pid, $instrument_name)
    {
        $this->pid = $pid;
        $this->instrument = $instrument_name;
        parent::__construct($pid, $instrument_name);
    }

    public function getAllInstancesFlat($record_id, $display_fields, $event_id=null) {

        global $module;

        $this->record_id = $record_id;
        $instances = $this->getAllInstances($record_id, $event_id);

        $flat_results = array();
        $display_results = array();
        $id = array();

        // See if the event id is included
        if (empty($instances[$record_id][$event_id])) {
            foreach ($instances[$record_id] as $key => $value) {
                $id["instance"] = $key;

                // If there are no display fields specified, return them all.
                if (is_null($display_fields)) {
                    $flat_results[] = $value;
                } else {
                    $display_results = array_intersect_key($value, array_flip($display_fields));
                }
                $flat_results[] = array_merge($id, $display_results);
            }

        } else if (!empty($event_id)) {
            foreach ($instances[$record_id][$event_id] as $key => $value) {
                $id["instance"] = $key;

                // If there are no display fields specified, return them all.
                if (is_null($display_fields)) {
                    $flat_results[] = $value;
                } else {
                    $display_results = array_intersect_key($value, array_flip($display_fields));
                }
                $flat_results[] = array_merge($id, $display_results);
            }
        } else {
            $flat_results = null;
        }

        return $flat_results;
    }

}
