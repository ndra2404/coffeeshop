<?php
defined('BASEPATH') or exit('No direct script access allowed');

class General_model extends CI_Model
{

	public function getAll($table)
    {
        $this->db->from($table);
        $query = $this->db->get();
        return $query->result();
    }

	public function joinTable($table, $tables = array(),$select="*")
	{
		$this->db->from($table);
		foreach ($tables as $key => $value) {
			$this->db->join($key, $value);
		}
		$this->db->select($select);
		$query = $this->db->get();
		return $query->result();
	}
}
