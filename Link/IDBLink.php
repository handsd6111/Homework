<?php
interface IDBLink
{
    function Select($table, $var, $option);

    function Insert($table, $var, $data);

    function Update($table, $var, $option);

    function Delete($table, $option);
}
