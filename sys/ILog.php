<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace jarekkozak\sys;
/**
 *
 * @author Jarosław Kozak <jaroslaw.kozak68@gmail.com>
 */
interface ILog
{
    const INFO = 'INFO';
    const ERROR = 'ERROR';
    const WARN = 'WARN';
    public function log($message,$type = ILog::INFO);

}