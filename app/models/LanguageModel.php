<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LanguageModel extends CI_Model {

    public function getAllTranslations() {
        $this->db->select('label, english, french');
        $this->db->from('language_translation_t');
        $this->db->where('display', 'Y');
        $query = $this->db->get();
        $result = [];
        foreach ($query->result_array() as $row) {
            $result[$row['label']] = [
                'english' => $row['english'],
                'french'  => $row['french']
            ];
        }
        return $result;
    }
}
