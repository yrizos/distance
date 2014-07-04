<?php

namespace DistanceMatrix;

class Api
{
    const ENDPOINT       = "http://maps.googleapis.com/maps/api/distancematrix";
    const MODE_DRIVING   = "driving";
    const MODE_WALKING   = "walking";
    const MODE_BICYCLING = "bicycling";
    const UNITS_METRIC   = "metric";
    const UNITS_IMPERIAL = "imperial";
    const OUTPUT_XML     = "xml";
    const OUTPUT_JSON    = "json";
    const LENGTH_LIMIT   = 2000;

    private $origins = array();
    private $destinations = array();
    private $output = self::OUTPUT_JSON;

    private $options =
        [
            "key"      => null,
            "language" => "en",
            "mode"  => null,
            "units" => null,
        ];

    public function __construct($apiKey = null)
    {
        if (!is_null($apiKey)) $this->setApiKey($apiKey);
    }

    public function setOutput($output)
    {
        $allowed      = [self::OUTPUT_JSON, self::OUTPUT_XML];
        $this->output = in_array($output, $allowed) ? $output : self::OUTPUT_JSON;

        return $this;
    }

    public function getOutput()
    {
        return $this->output;
    }

    public function addOrigin($origin)
    {
        $origin = is_string($origin) ? trim($origin) : null;

        if (
            !is_null($origin)
            && !in_array($origin, $this->origins)
        ) {
            $this->origins[] = $origin;
        }

        return $this;
    }

    public function addDestination($destination)
    {
        $destination = is_string($destination) ? trim($destination) : null;

        if (
            !is_null($destination)
            && !in_array($destination, $this->destinations)
        ) {
            $this->destinations[] = $destination;
        }

        return $this;
    }

    public function setOption($key, $value)
    {
        if (!array_key_exists($key, $this->options)) return $this;

        $this->options[$key] = $value;

        return $this;
    }

    public function getOption($key)
    {
        return
            isset($this->options[$key])
                ? $this->options[$key]
                : null;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setApiKey($apiKey)
    {
        $apiKey = is_string($apiKey) ? trim($apiKey) : null;

        return $this->setOption("key", $apiKey);
    }

    public function getApiKey()
    {
        return $this->getOption("key");
    }

    public function setMode($mode)
    {
        $allowed = [self::MODE_DRIVING, self::MODE_BICYCLING, self::MODE_WALKING];
        $mode    = in_array($mode, $allowed) ? $mode : self::MODE_DRIVING;

        return $this->setOption("mode", $mode);
    }

    public function getMode()
    {
        return $this->getOption("mode");
    }

    public function setLanguage($language)
    {
        $language = is_string($language) ? trim(strtolower($language)) : "en";

        return $this->setOption("language", $language);
    }

    public function getLanguage()
    {
        return $this->getOption("language");
    }

    public function setUnits($units)
    {
        $allowed = [self::UNITS_METRIC, self::UNITS_IMPERIAL];
        $units   = in_array($units, $allowed) ? $units : self::UNITS_METRIC;

        return $this->setOption("units", $units);
    }

    public function getUnits()
    {
        return $this->getOption("units");
    }

    public function getRequestString()
    {
        $origins      = $this->origins;
        $destinations = $this->destinations;

        if (empty($origins)) throw new \RuntimeException("You must set at least one origin point.");
        if (empty($destinations)) throw new \RuntimeException("You must set at least one destination point.");

        foreach ($origins as $key => $value) $origins[$key] = urlencode($value);
        foreach ($destinations as $key => $value) $destinations[$key] = urlencode($value);

        $string = $this->getOutput();
        $string .= "?origins=" . implode("|", $origins) . "&" . "destinations=" . implode("|", $destinations);

        $options = $this->getOptions();
        foreach ($options as $key => $value) {
            if (empty($value)) continue;

            $string .= "&{$key}=" . urlencode($value);
        }

        if (strlen($string) > self::LENGTH_LIMIT)
            throw new \RuntimeException("The request query cannot be larger than " . self::LENGTH_LIMIT . " characters.");

        return "/" . $string;
    }

    public function run()
    {
        $string = self::ENDPOINT . $this->getRequestString();
        $result = @file_get_contents($string);

        return $result;
    }

}

