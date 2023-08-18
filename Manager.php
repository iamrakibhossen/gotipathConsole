<?php

namespace  gotipathConsole;
use Illuminate\Database\Capsule\Manager as Capsule;

class Manager{


    //Constructor Value will be provided by the module
    public function __construct()
    {

    }


    public function getServerDetails($params)
    {
        $serverId = $params['serverid'];
        $serverDetails = Capsule::table('tblservers')->where('id', $serverId)->first();
        return $serverDetails;
    }

    public function getServerConfigOptions($params)
    {
        $serverId = $params['serverid'];
        $serverConfigOptions = Capsule::table('tblserverconfigoptions')->where('serverid', $serverId)->get();
        return $serverConfigOptions;
    }

    public function getServerCustomFields($params)
    {
        $serverId = $params['serverid'];
        $serverCustomFields = Capsule::table('tblcustomfields')->where('relid', $serverId)->get();
        return $serverCustomFields;
    }

    public function getServerCustomFieldValues($params)
    {
        $serverId = $params['serverid'];
        $serverCustomFieldValues = Capsule::table('tblcustomfieldsvalues')->where('relid', $serverId)->get();
        return $serverCustomFieldValues;
    }

    public function getServerGroup($params)
    {
        $serverId = $params['serverid'];
        $serverGroup = Capsule::table('tblservergroupsrel')->where('serverid', $serverId)->get();
        return $serverGroup;
    }

    public function getServerGroupRelation($params)
    {
        $serverId = $params['serverid'];
        $serverGroupRelation = Capsule::table('tblservergroupsrel')->where('serverid', $serverId)->get();
        return $serverGroupRelation;
    }

    public function getServerGroupRelationGroup($params)
    {
        $serverId = $params['serverid'];
        $serverGroupRelationGroup = Capsule::table('tblservergroupsrel')->where('serverid', $serverId)->get();
        return $serverGroupRelationGroup;
    }

    public function getServerGroupRelationServer($params)
    {
        $serverId = $params['serverid'];
        $serverGroupRelationServer = Capsule::table('tblservergroupsrel')->where('serverid', $serverId)->get();
        return $serverGroupRelationServer;
    }


}
