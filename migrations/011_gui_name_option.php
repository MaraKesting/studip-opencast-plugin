<?php

/**
 * Created by PhpStorm.
 * User: jayjay
 * Date: 02.02.17
 * Time: 15:06
 */
class GuiNameOption extends Migration
{

    /**
     * set migration description
     *
     * @return string description
     */
    function description() {
        return "Add config options to database";
    }

    function up() {
        $config = Config::get();

        try {
            $config->create('OPENCAST_GUI_NAME', array("type" => "string", "value" => "OpenCast", "description" => "Benennung des Plugins in der GUI für Anwender", "section" => "opencast"));
        } catch (InvalidArgumentException $e) {

        }
    }
}