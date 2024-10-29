<?php

class Sites_meta {

    public $sitm_id,
            $sitm_sit_id,
            $sitm_key, //type plugin/core/info_plugin
            $sitm_value, //name    
            $sitm_infos; // version     /infos_plugin


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

    public function sitm_id() {
        return $this->sitm_id;
    }

    public function sitm_sit_id() {
        return $this->sitm_sit_id;
    }

    public function sitm_key() {
        return stripslashes(htmlspecialchars_decode($this->sitm_key));
    }
    public function sitm_value() {
        return stripslashes(htmlspecialchars_decode($this->sitm_value));
    }
        public function sitm_infos() {
        return stripslashes(htmlspecialchars_decode($this->sitm_infos));
    }
    
    public function setSitm_id($valeur) {
        if (is_numeric($valeur)) {
            return $this->sit_id = $valeur;
        } else {
            trigger_error('setSitm_id est un ID de type int');
        }
    }
    
    public function setSitm_sit_id($valeur) {
        if (is_numeric($valeur)) {
            return $this->sitm_sit_id = $valeur;
        } else {
            trigger_error('setSitem_sit_id est un ID de type int');
        }
    }
    public function setSitm_key($valeur) {
        return $this->sitm_key = addslashes(htmlspecialchars($valeur));
    }

    public function setSitm_value($valeur) {
        return $this->sitm_value = addslashes(htmlspecialchars($valeur));
    }
    
        public function setSitm_infos($valeur) {
        return $this->sitm_infos = addslashes(htmlspecialchars($valeur));
    }


}
