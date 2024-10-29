<?php

class Sites {

    public $sit_id,
            $sit_name,
            $sit_url,
            $sit_date;

    public function __construct(array $donnees) {
        $this->hydrate($donnees);
        $this->type = strtolower(get_class($this));
    }

    public function hydrate(array $donnees) {
        foreach ($donnees as $key => $value) {
            $method = 'set' . ucfirst($key);

            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    public function sit_id() {
        return $this->sit_id;
    }

    public function sit_name() {
        return stripslashes(htmlspecialchars_decode($this->sit_name));
    }

    public function sit_url() {
        return stripslashes(htmlspecialchars_decode($this->sit_url));
    }
    public function sit_date() {
        return $this->sit_date;
    }

    public function setSit_id($valeur) {
        if (is_numeric($valeur)) {
            return $this->sit_id = trim($valeur);
        } else {
            trigger_error('setSit_id est un ID de type int');
        }
    }

    public function setSit_name($valeur) {
        return $this->sit_name = addslashes(htmlspecialchars($valeur));
    }

    public function setSit_url($valeur) {
        return $this->sit_url = addslashes(htmlspecialchars($valeur));
    }
    public function setSit_date($valeur) {
        return $this->sit_date = $valeur;
    }

}
