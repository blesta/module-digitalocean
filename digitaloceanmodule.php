<?php

class Digitaloceanmodule extends Module
{

    public function __construct()
    {
        Loader::loadComponents($this, array("Input"));
        Language::loadLang("lang", null, dirname(__FILE__) . DS . "language" . DS);
        Loader::loadHelpers($this, array("Html"));
        $this->loadConfig(dirname(__FILE__) . DS . "config.json");
    }

    public function getAdminTabs($package)
    {
        return array();
    }

    public function getClientTabs($package)
    {
        return array(
            'managementoptions' => Language::_("Digitaloceanmodule.managementoptions", true),
            'actionshistory' => Language::_("Digitaloceanmodule.actionshistory", true),
        );
    }

    public function actionshistory($package, $service, array $get = null, array $post = null, array $files = null)
    {
        $this->view = new View("actionshistory", "default");
        $this->view->base_uri = $this->base_uri;
        Loader::loadHelpers($this, array("Form", "Html"));
        $service_fields = $this->serviceFieldsToObject($service->fields);
        $module_row = $this->getModuleRow($package->module_row);
        $api = $this->getApi($module_row->meta->apiKey);

        $this->view->set("actions", $api->getlongGetResults("droplets/{$service_fields->droplet_id}/actions")->actions);
        $this->view->set("module_row", $module_row);
        $this->view->set("service_fields", $service_fields);

        $this->view->setDefaultView("components" . DS . "modules" . DS . "digitaloceanmodule" . DS);
        return $this->view->fetch();
    }

    public function managementoptions($package, $service, array $get = null, array $post = null, array $files = null)
    {
        $this->view = new View("managementoptions", "default");
        $this->view->base_uri = $this->base_uri;
        Loader::loadHelpers($this, array("Form", "Html"));
        $service_fields = $this->serviceFieldsToObject($service->fields);
        $module_row = $this->getModuleRow($package->module_row);
        $api = $this->getApi($module_row->meta->apiKey);
        Loader::loadModels($this, array("Services"));

        if (isset($post['power_cycle'])) {
            $result = $api->getPostResults(
                "droplets/{$service_fields->droplet_id}/actions",
                array("type" => "power_cycle")
            );
            if (isset($result->message)) {
                $fa = array(
                    0 => array(
                        "result" => isset($result->message)
                        ? str_replace("Droplet", "Server", $result->message)
                        : Language::_("Digitaloceanmodule.empty_invalid_values", true)
                    )
                );
                $this->Input->setErrors($fa);
            }
        }

        if (isset($post['shutdown'])) {
            $result = $api->getPostResults(
                "droplets/{$service_fields->droplet_id}/actions",
                array("type" => "shutdown")
            );
            if (isset($result->message)) {
                $fa = array(
                    0 => array(
                        "result" => isset($result->message)
                        ? str_replace("Droplet", "Server", $result->message)
                        : Language::_("Digitaloceanmodule.empty_invalid_values", true)
                    )
                );
                $this->Input->setErrors($fa);
            }
        }

        if (isset($post['power_on'])) {
            $result = $api->getPostResults(
                "droplets/{$service_fields->droplet_id}/actions",
                array("type" => "power_on")
            );
            if (isset($result->message)) {
                $fa = array(
                    0 => array(
                        "result" => isset($result->message)
                        ? str_replace("Droplet", "Server", $result->message)
                        : Language::_("Digitaloceanmodule.empty_invalid_values", true)
                    )
                );
                $this->Input->setErrors($fa);
            }
        }

        if (isset($post['newsnapshot'])) {
            $result = $api->getPostResults(
                "droplets/{$service_fields->droplet_id}/actions",
                array("type" => "snapshot")
            );
            if (isset($result->message)) {
                $fa = array(
                    0 => array(
                        "result" => isset($result->message)
                        ? str_replace("Droplet", "Server", $result->message)
                        : Language::_("Digitaloceanmodule.empty_invalid_values", true)
                    )
                );
                $this->Input->setErrors($fa);
            }
        }

        if (isset($post['restore'])) {
            $result = $api->getPostResults(
                "droplets/{$service_fields->droplet_id}/actions",
                array("type" => "restore", "image" => $post['restore_image'])
            );
            if (isset($result->message)) {
                $fa = array(
                    0 => array(
                        "result" => isset($result->message)
                        ? str_replace("Droplet", "Server", $result->message)
                        : Language::_("Digitaloceanmodule.empty_invalid_values", true)
                    )
                );
                $this->Input->setErrors($fa);
            }
        }

        if (isset($post['restorefromsnapshots'])) {
            $result = $api->getPostResults(
                "droplets/{$service_fields->droplet_id}/actions",
                array("type" => "restore", "image" => $post['restore_snapshots'])
            );
            if (isset($result->message)) {
                $fa = array(
                    0 => array(
                        "result" => isset($result->message)
                        ? str_replace("Droplet", "Server", $result->message)
                        : Language::_("Digitaloceanmodule.empty_invalid_values", true)
                    )
                );
                $this->Input->setErrors($fa);
            }
        }

        if (isset($post['rebuild'])) {
            $result = $api->getPostResults(
                "droplets/{$service_fields->droplet_id}/actions",
                array("type" => "rebuild", "image" => $post['rebuild_image'])
            );
            if (isset($result->message)) {
                $fa = array(
                    0 => array(
                        "result" => isset($result->message)
                        ? str_replace("Droplet", "Server", $result->message)
                        : Language::_("Digitaloceanmodule.empty_invalid_values", true)
                    )
                );
                $this->Input->setErrors($fa);
            }
        }

        if (isset($post['kernelchange'])) {
            $result = $api->getPostResults(
                "droplets/{$service_fields->droplet_id}/actions",
                array("type" => "change_kernel", "kernel" => $post['new_kernel'])
            );
            if (isset($result->message)) {
                $fa = array(
                    0 => array(
                        "result" => isset($result->message)
                        ? str_replace("Droplet", "Server", $result->message)
                        : Language::_("Digitaloceanmodule.empty_invalid_values", true)
                    )
                );
                $this->Input->setErrors($fa);
            }
        }

        $ip_address = null;
        $droplet_info = $api->getGetResults("droplets/{$service_fields->droplet_id}")->droplet;
        if (!empty($droplet_info->networks->v4)) {
            foreach ($droplet_info->networks->v4 as $ipkey => $ipvalue) {
                if ($droplet_info->networks->v4[$ipkey]->type === "public") {
                    $ip_address = $droplet_info->networks->v4[$ipkey]->ip_address;
                }
            }
        }
        if (!empty($droplet_info->networks->v6)) {
            foreach ($droplet_info->networks->v6 as $ipv6key => $ipv6value) {
                if ($droplet_info->networks->v6[$ipv6key]->type === "public") {
                    $ip_address = $droplet_info->networks->v6[$ipv6key]->ip_address;
                }
            }
        }

        $this->view->set("ip_address", $ip_address);
        $this->view->set("droplet_info", $droplet_info);
        $this->view->set("rebuild_images", $this->getImagesDropdown($package->id));
        $this->view->set("kernels", $this->getkernelDropdown($module_row, $service_fields->droplet_id));
        $this->view->set("restore_snapshots", $this->getsnapshotsDropdown($module_row, $service_fields->droplet_id));
        $this->view->set("restore_images", $this->getRestoreImagesDropdown($module_row, $service_fields->droplet_id));
        $this->view->set("kernel_id", (($droplet_info->kernel) ? $droplet_info->kernel->id : null));
        $this->view->set("module_row", $module_row);
        $this->view->set("service_fields", $service_fields);

        $this->view->setDefaultView("components" . DS . "modules" . DS . "digitaloceanmodule" . DS);
        return $this->view->fetch();
    }

    public function getsnapshotsDropdown($module_row, $droplet_id)
    {
        $api = $this->getApi($module_row->meta->apiKey);
        $result = $api->getlongGetResults("droplets/{$droplet_id}/snapshots")->snapshots;
        $dp = array();
        foreach ($result as $key => $value) {
            if ($result[$key]->type === "snapshot") {
                $dp[$result[$key]->id] = $result[$key]->name . " - " . $result[$key]->distribution;
            }
        }
        return $dp;
    }

    public function getkernelDropdown($module_row, $droplet_id)
    {
        $api = $this->getApi($module_row->meta->apiKey);
        $result = $api->getlongGetResults("droplets/{$droplet_id}/kernels")->kernels;
        $dp = array();
        foreach ($result as $key => $value) {
            $dp[$result[$key]->id] = $result[$key]->name;
        }
        return $dp;
    }

    public function getImagesDropdown($p_id)
    {
        Loader::loadModels($this, array("PackageOptions"));
        $pkgs = $this->PackageOptions->getByPackageId($p_id);
        $array = array();
        foreach ($pkgs as $key => $value) {
            if (isset($pkgs[$key]->name)
                && isset($pkgs[$key]->type)
                && $pkgs[$key]->name === "image"
                && $pkgs[$key]->type === "select"
            ) {
                foreach ($pkgs[$key]->values as $vkey => $vvalue) {
                    $array[$pkgs[$key]->values[$vkey]->value] = $pkgs[$key]->values[$vkey]->name;
                }
            }
        }
        return $array;
    }

    public function getRestoreImagesDropdown($module_row, $droplet_id)
    {
        $api = $this->getApi($module_row->meta->apiKey);
        $result = $api->getlongGetResults("droplets/{$droplet_id}/backups")->backups;
        $dp = array();
        foreach ($result as $key => $value) {
            if ($result[$key]->type === "backup") {
                $dp[$result[$key]->id] = $result[$key]->name . " - " . $result[$key]->distribution;
            }
        }
        return $dp;
    }

    public function moduleRowName()
    {
        return Language::_("Digitaloceanmodule.module_row", true);
    }

    public function moduleRowNamePlural()
    {
        return Language::_("Digitaloceanmodule.module_row_plural", true);
    }

    public function moduleGroupName()
    {
        return Language::_("Digitaloceanmodule.module_group", true);
    }

    public function moduleRowMetaKey()
    {
        return "name";
    }

    public function getGroupOrderOptions()
    {
        return array('first' => Language::_("Digitaloceanmodule.order_options.first", true));
    }

    public function selectModuleRow($module_group_id)
    {
        if (!isset($this->ModuleManager)) {
            Loader::loadModels($this, array("ModuleManager"));
        }

        $group = $this->ModuleManager->getGroup($module_group_id);

        if ($group) {
            switch ($group->add_order) {
                default:
                case "first":
                    foreach ($group->rows as $row) {
                        return $row->id;
                    }

                    break;
            }
        }
        return 0;
    }

    public function managecloud($package, $service, array $get = null, array $post = null, array $files = null)
    {
        $this->view = new View("managecloud", "default");
        $this->view->setDefaultView("components" . DS . "modules" . DS . "digitalocean" . DS);
        Loader::loadHelpers($this, array("Form", "Html"));
        $row = $this->getModuleRow($package->module_row);
        $api = $this->getApi($row->meta->apiKey);
        $service_fields = $this->serviceFieldsToObject($service->fields);

        $this->view->set("service_fields", $service_fields);
        return $this->view->fetch();
    }

    public function getPackageFields($vars = null)
    {
        Loader::loadHelpers($this, array("Html"));

        $fields = new ModuleFields();

        $module_row = null;
        if (isset($vars->module_group) && $vars->module_group == "") {
            if (isset($vars->module_row) && $vars->module_row > 0) {
                $module_row = $this->getModuleRow($vars->module_row);
            } else {
                $rows = $this->getModuleRows();
                if (isset($rows[0])) {
                    $module_row = $rows[0];
                }
                unset($rows);
            }
        } else {
            $rows = $this->getModuleRows($vars->module_group);
            if (isset($rows[0])) {
                $module_row = $rows[0];
            }
            unset($rows);
        }
        $ssh_options = $this->getSshDropdown($module_row);
        $size_options = $this->getSizesDropdown($module_row);

        $sshkey = $fields->label(Language::_("Digitaloceanmodule.global_sshkey", true), "global_sshkey");
        $tooltip = $fields->tooltip(Language::_("Digitaloceanmodule.global_sshkey.tooltip", true));
        $sshkey->attach($tooltip);
        $sshkey->attach(
            $fields->fieldSelect(
                "meta[global_sshkey]",
                $ssh_options,
                $this->Html->ifSet($vars->meta['global_sshkey']),
                array('id' => "global_sshkey")
            )
        );
        $fields->setField($sshkey);

        $sizes = $fields->label(Language::_("Digitaloceanmodule.size", true), "size");
        $sizes->attach(
            $fields->fieldSelect(
                "meta[size]",
                $size_options,
                $this->Html->ifSet($vars->meta['size']),
                array('id' => "size")
            )
        );
        $fields->setField($sizes);

        return $fields;
    }

    public function getAllImagesDropdown($package)
    {
        $row = $this->getModuleRow($package->module_row);
        $api = $this->getApi($row->meta->apiKey);
        $result = $api->getlongGetResults("images");
        $dp = array();
        foreach ($result->images as $key => $value) {
            $dp[$result->images[$key]->slug] = $result->images[$key]->slug;
        }
        return $dp;
    }

    public function getAllRegionsDropdown($package)
    {
        $row = $this->getModuleRow($package->module_row);
        $api = $this->getApi($row->meta->apiKey);
        $result = $api->getlongGetResults("regions");
        $dp = array();
        foreach ($result->regions as $key => $value) {
            $dp[$result->regions[$key]->slug] = $result->regions[$key]->slug;
        }
        return $dp;
    }

    public function getSshDropdown($module_row)
    {
        $api = $this->getApi($module_row->meta->apiKey);
        $result = $api->getlongGetResults("account/keys");
        $dp = array();
        foreach ($result->ssh_keys as $key => $value) {
            $dp[$result->ssh_keys[$key]->id] = $result->ssh_keys[$key]->name;
        }
        return $dp;
    }

    public function getSizesDropdown($module_row)
    {
        $api = $this->getApi($module_row->meta->apiKey);
        $result = $api->getlongGetResults("sizes");
        $dp = array();
        foreach ($result->sizes as $key => $value) {
            if ($result->sizes[$key]->available == true) {
                $dp[$result->sizes[$key]->slug] = $result->sizes[$key]->slug;
            }
        }
        return $dp;
    }

    public function getEmailTags()
    {
        return array(
            'package' => array('package', 'size'),
            'service' => array(
                'droplet_id',
                'hostname',
                'region',
                'image',
                'backups',
                'ipv6',
                'private_networking',
                'client_sshkey',
                'user_data'
            )
        );
    }

    public function addPackage(array $vars = null)
    {
        $meta = array();
        foreach ($vars['meta'] as $key => $value) {
            $meta[] = array(
                'key' => $key,
                'value' => $value,
                'encrypted' => 0
            );
        }
        return $meta;
    }

    public function editPackage($package, array $vars = null)
    {
        $meta = array();
        foreach ($vars['meta'] as $key => $value) {
            $meta[] = array(
                'key' => $key,
                'value' => $value,
                'encrypted' => 0
            );
        }
        return $meta;
    }

    public function manageModule($module, array &$vars)
    {
        $this->view = new View("manage", "default");
        $this->view->base_uri = $this->base_uri;
        $this->view->setDefaultView("components" . DS . "modules" . DS . "digitaloceanmodule" . DS);


        Loader::loadHelpers($this, array("Form", "Html", "Widget"));

        $this->view->set("module", $module);

        return $this->view->fetch();
    }

    public function manageAddRow(array &$vars)
    {
        $this->view = new View("add_row", "default");
        $this->view->base_uri = $this->base_uri;
        $this->view->setDefaultView("components" . DS . "modules" . DS . "digitaloceanmodule" . DS);

        Loader::loadHelpers($this, array("Form", "Html", "Widget"));

        $this->view->set("vars", (object) $vars);
        return $this->view->fetch();
    }

    public function manageEditRow($module_row, array &$vars)
    {
        $this->view = new View("edit_row", "default");
        $this->view->base_uri = $this->base_uri;
        $this->view->setDefaultView("components" . DS . "modules" . DS . "digitaloceanmodule" . DS);


        Loader::loadHelpers($this, array("Form", "Html", "Widget"));

        if (empty($vars)) {
            $vars = $module_row->meta;
        }

        $this->view->set("vars", (object) $vars);
        return $this->view->fetch();
    }

    public function addModuleRow(array &$vars)
    {
        $meta_fields = array("name", "apiKey");
        $encrypted_fields = array("apiKey");

        $this->Input->setRules($this->getRowRules($vars));

        if ($this->Input->validates($vars)) {
            $meta = array();
            foreach ($vars as $key => $value) {
                if (in_array($key, $meta_fields)) {
                    $meta[] = array(
                        'key' => $key,
                        'value' => $value,
                        'encrypted' => in_array($key, $encrypted_fields) ? 1 : 0
                    );
                }
            }

            return $meta;
        }
    }

    public function editModuleRow($module_row, array &$vars)
    {
        $meta_fields = array("name", "apiKey");
        $encrypted_fields = array("apiKey");

        $this->Input->setRules($this->getRowRules($vars));

        if ($this->Input->validates($vars)) {
            $meta = array();
            foreach ($vars as $key => $value) {
                if (in_array($key, $meta_fields)) {
                    $meta[] = array(
                        'key' => $key,
                        'value' => $value,
                        'encrypted' => in_array($key, $encrypted_fields) ? 1 : 0
                    );
                }
            }

            return $meta;
        }
    }

    public function deleteModuleRow($module_row)
    {

    }

    public function getServiceName($service)
    {
        foreach ($service->fields as $field) {
            if ($field->key == "hostname") {
                return $field->value;
            }
        }
        return null;
    }

    public function getPackageServiceName($package, array $vars = null)
    {
        if (isset($vars['hostname'])) {
            return $vars['hostname'];
        }
        return null;
    }

    public function getAdminAddFields($package, $vars = null)
    {
        Loader::loadHelpers($this, array("Html"));

        $fields = new ModuleFields();

        $domain = $fields->label(Language::_("Digitaloceanmodule.hostname", true), "hostname");
        $domain->attach(
            $fields->fieldText(
                "hostname",
                $this->Html->ifSet($vars->hostname, $this->Html->ifSet($vars->hostname)),
                array('id' => "hostname")
            )
        );
        $fields->setField($domain);

        $region = $fields->label(Language::_("Digitaloceanmodule.region", true), "region");
        $region->attach(
            $fields->fieldSelect("region", $this->getAllRegionsDropdown($package)),
            array('id' => "region")
        );
        $fields->setField($region);

        $image = $fields->label(Language::_("Digitaloceanmodule.image", true), "image");
        $image->attach($fields->fieldSelect("image", $this->getallImagesDropdown($package)), array('id' => "image"));
        $fields->setField($image);

        $client_sshkey = $fields->label(Language::_("Digitaloceanmodule.client_sshkey", true), "client_sshkey");
        $client_sshkey->attach(
            $fields->fieldTextArea(
                "client_sshkey",
                $this->Html->ifSet($vars->client_sshkey, $this->Html->ifSet($vars->client_sshkey)),
                array('id' => "client_sshkey")
            )
        );
        $fields->setField($client_sshkey);

        return $fields;
    }

    public function getClientAddFields($package, $vars = null)
    {
        Loader::loadHelpers($this, array("Html"));

        $fields = new ModuleFields();
        $domain = $fields->label(Language::_("Digitaloceanmodule.hostname", true), "hostname");
        $domain->attach(
            $fields->fieldText(
                "hostname",
                $this->Html->ifSet($vars->hostname, $this->Html->ifSet($vars->hostname)),
                array('id' => "hostname")
            )
        );
        $fields->setField($domain);

        $region = $fields->label(Language::_("Digitaloceanmodule.region", true), "region");
        $region->attach(
            $fields->fieldSelect("region", $this->getAllRegionsDropdown($package)),
            array('id' => "region")
        );
        $fields->setField($region);

        $image = $fields->label(Language::_("Digitaloceanmodule.image", true), "image");
        $image->attach($fields->fieldSelect("image", $this->getallImagesDropdown($package)), array('id' => "image"));
        $fields->setField($image);

        $client_sshkey = $fields->label(Language::_("Digitaloceanmodule.client_sshkey", true), "client_sshkey");
        $client_sshkey->attach(
            $fields->fieldTextArea(
                "client_sshkey",
                $this->Html->ifSet($vars->client_sshkey),
                array('id' => "client_sshkey")
            )
        );
        $fields->setField($client_sshkey);

        return $fields;
    }

    public function getAdminEditFields($package, $vars = null)
    {
        Loader::loadHelpers($this, array("Html"));
        $fields = new ModuleFields();
        $domain = $fields->label(Language::_("Digitaloceanmodule.hostname", true), "hostname");
        $domain->attach(
            $fields->fieldText(
                "hostname",
                $this->Html->ifSet($vars->hostname, $this->Html->ifSet($vars->hostname)),
                array('id' => "hostname")
            )
        );
        $fields->setField($domain);

        return $fields;
    }

    public function validateService($package, array $vars = null, $edit = false)
    {
        $rules = array();

        $this->Input->setRules($rules);
        return $this->Input->validates($vars);
    }

    public function addService(
        $package,
        array $vars = null,
        $parent_package = null,
        $parent_service = null,
        $status = "pending"
    ) {
        $row = $this->getModuleRow($package->module_row);
        $api = $this->getApi($row->meta->apiKey);

        if (!$row) {
            $this->Input->setErrors(
                array(
                    'module_row' => array(
                        'missing' => Language::_("Digitaloceanmodule.!error.module_row.missing", true)
                    )
                )
            );
            return;
        }
        $ip_address = null;

        $client_dname = $vars['hostname'];
        $client_sshkey = $vars['client_sshkey'];

        Loader::loadModels($this, array("Clients"));
        if (isset($vars['client_id']) && ($client = $this->Clients->get($vars['client_id'], false))) {
            $client_id_code = $client->id_code;
        }


        $ssh_key = array();
        $ssh_key['name'] = $client_id_code . " - " . $vars['hostname'];
        $ssh_key['public_key'] = isset($vars['client_sshkey']) ? $vars['client_sshkey'] : $vars['client_sshkey'];
        $sshkey_result = $api->getPostResults("account/keys", $ssh_key);
        if (isset($sshkey_result->ssh_key->id)
            && isset($sshkey_result->ssh_key->name)
            && $sshkey_result->ssh_key->name === $ssh_key['name']
        ) {
            $this->log(
                "Validating Client SSH Key {$sshkey_result->ssh_key->name}",
                serialize("sshkey_validate"),
                "input",
                true
            );
            $api->getDeleteResults("account/keys/{$sshkey_result->ssh_key->id}");
        } else {
            if ($sshkey_result->message === "Ssh key SSH Key is already in use on your account") {
                $sshkey_result->message = Language::_("Digitaloceanmodule.chooseanothersshkey", true);
            }
            $fa = array(
                0 => array(
                    "result" => isset($sshkey_result->message)
                        ? $sshkey_result->message
                        : Language::_("Digitaloceanmodule.empty_invalid_values", true)
                )
            );
            $this->Input->setErrors($fa);
        }
        
        $params = $vars;// Fix for front-end ordering

        if ($vars['use_module'] == "true") {
            $sshkey_result = $api->getPostResults("account/keys", $ssh_key);
            if (isset($sshkey_result->ssh_key->id)
                && isset($sshkey_result->ssh_key->name)
                && $sshkey_result->ssh_key->name === $ssh_key['name']
            ) {
                $this->log(
                    "Create Client SSH Key {$sshkey_result->ssh_key->name}",
                    serialize("sshkey_create"),
                    "input",
                    true
                );
                $vars['client_sshkey'] = $sshkey_result->ssh_key->fingerprint;
                $this->log("Set client_sshkey variable", print_r($vars['client_sshkey'], true), "input", true);
                $params = $this->getFieldsFromInput((array) $vars, $package);
                $result = $api->getPostResults("droplets", $params);
                $this->log("API CREATE RESULTS" . print_r($result, true), print_r($result, true), "input", true);
                if (isset($result->droplet->id)
                    && isset($result->droplet->name)
                    && $result->droplet->name === $vars['hostname']
                ) {
                    $this->log(
                        "Create New Droplet {$result->droplet->id} - {$result->droplet->name}",
                        serialize("droplet_create"),
                        "input",
                        true
                    );
                    $client_did = $result->droplet->id;
                    $client_dname = $result->droplet->name;
                    $client_sshkey = $sshkey_result->ssh_key->fingerprint;
                } else {
                    $fa = array(
                        0 => array(
                            "result" => isset($result->message)
                                ? str_replace("Droplet", "Server", $result->message)
                                : Language::_("Digitaloceanmodule.empty_invalid_values", true)
                        )
                    );
                    $this->Input->setErrors($fa);
                }
            }

            if ($this->Input->errors()) {
                return;
            }
        }

        return array(
            array(
                'key' => "droplet_id",
                'value' => isset($client_did) ? $client_did : null,
                'encrypted' => 0
            ),
            array(
                'key' => "hostname",
                'value' => isset($client_dname) ? $client_dname : null,
                'encrypted' => 0
            ),
            array(
                'key' => "region",
                'value' => isset($params['region']) ? $params['region'] : null,
                'encrypted' => 0
            ),
            array(
                'key' => "image",
                'value' => isset($params['image']) ? $params['image'] : null,
                'encrypted' => 0
            ),
            array(
                'key' => "backups",
                'value' => isset($params['backups']) ? $params['backups'] : null,
                'encrypted' => 0
            ),
            array(
                'key' => "ipv6",
                'value' => isset($params['ipv6']) ? $params['ipv6'] : null,
                'encrypted' => 0
            ),
            array(
                'key' => "private_networking",
                'value' => isset($params['private_networking']) ? $params['private_networking'] : null,
                'encrypted' => 0
            ),
            array(
                'key' => "user_data",
                'value' => isset($params['user_data']) ? $params['user_data'] : null,
                'encrypted' => 0
            ),
            array(
                'key' => "client_sshkey",
                'value' => isset($params['client_sshkey']) ? $params['client_sshkey'] : null,
                'encrypted' => 0
            )
        );
    }

    private function getFieldsFromInput(array $vars, $package)
    {
        $fields = array(
            'name' => isset($vars['hostname']) ? $vars['hostname'] : null,
            'region' => isset($vars['configoptions']['region']) ? $vars['configoptions']['region'] : "nyc1",
            'size' => $package->meta->size,
            'image' => isset($vars['image']) ? $vars['image'] : $vars['image'],
            'ssh_keys' => array(
                isset($vars['global_sshkey']) ? $vars['global_sshkey'] : $vars['client_sshkey'],
                isset($vars['client_sshkey']) ? $vars['client_sshkey'] : $vars['client_sshkey']
            ),
            'backups' => isset($vars['configoptions']['backups']) ? $vars['configoptions']['backups'] : null,
            'ipv6' => isset($vars['configoptions']['ipv6']) ? $vars['configoptions']['ipv6'] : null,
            'private_networking' => isset($vars['configoptions']['private_networking'])
                ? $vars['configoptions']['private_networking']
                : null,
            'user_data' => isset($vars['configoptions']['user_data']) ? $vars['configoptions']['user_data'] : null,
        );
        return $fields;
    }

    public function editService(
        $package,
        $service,
        array $vars = null,
        $parent_package = null,
        $parent_service = null
    ) {
        $row = $this->getModuleRow($package->module_row);
        $api = $this->getApi($row->meta->apiKey);
        $service_fields = $this->serviceFieldsToObject($service->fields);

        if ($vars['use_module'] == "true") {
            if ($service_fields->hostname !== $vars['hostname']) {
                $rename = $api->getPostResults(
                    "droplets/{$service_fields->droplet_id}/actions",
                    array('type' => 'rename', "name" => $vars['hostname'])
                );
                if (isset($rename->action->type) && $rename->action->type == "rename") {
                    $this->log(
                        "Rename Droplet {$service_fields->droplet_id} - {$service_fields->hostname} TO {$vars['hostname']}",
                        serialize("droplet_rename"),
                        "input",
                        true
                    );
                } else {
                    $fa = array(
                        0 => array(
                            "result" => isset($rename->message)
                                ? str_replace("Droplet", "Server", $rename->message)
                                : Language::_("Digitaloceanmodule.empty_invalid_values", true)
                        )
                    );
                    $this->Input->setErrors($fa);
                }
            }

            if ($service_fields->image !== $vars['configoptions']['image']) {
                $rename = $api->getPostResults(
                    "droplets/{$service_fields->droplet_id}/actions",
                    array('type' => 'rebuild', "name" => $vars['configoptions']['image'])
                );
                if (isset($rename->action->type) && $rename->action->type == "rebuild") {
                    $this->log(
                        "Rebuild Droplet {$service_fields->droplet_id} - {$service_fields->hostname} TO {$vars['configoptions']['image']}",
                        serialize("droplet_rebuild"),
                        "input",
                        true
                    );
                } else {
                    $fa = array(
                        0 => array(
                            "result" => isset($rename->message)
                                ? str_replace("Droplet", "Server", $rename->message)
                                : Language::_("Digitaloceanmodule.empty_invalid_values", true)
                        )
                    );
                    $this->Input->setErrors($fa);
                }
            }

            if ($service_fields->backups === "enable") {
                $rename = $api->getPostResults(
                    "droplets/{$service_fields->droplet_id}/actions",
                    array('type' => 'disable_backups')
                );
                if (isset($rename->action->type) && $rename->action->type == "disable_backups") {
                    $this->log(
                        "Disable Backup For Droplet {$service_fields->droplet_id} - {$service_fields->hostname}",
                        serialize("droplet_backup_disable"),
                        "input",
                        true
                    );
                } else {
                    $fa = array(
                        0 => array(
                            "result" => isset($rename->message)
                                ? str_replace("Droplet", "Server", $rename->message)
                                : Language::_("Digitaloceanmodule.empty_invalid_values", true)
                        )
                    );
                    $this->Input->setErrors($fa);
                }
            } else {
                $fa = array(
                    0 => array(
                        "result" => "Backups can be enabled only during the creation of the droplet, not after."
                    )
                );
                $this->Input->setErrors($fa);
            }


            if ($service_fields->private_networking === "disable") {
                $rename = $api->getPostResults(
                    "droplets/{$service_fields->droplet_id}/actions",
                    array('type' => 'enable_private_networking')
                );
                if (isset($rename->action->type) && $rename->action->type == "private_networking") {
                    $this->log(
                        "Enable Private Networking For Droplet {$service_fields->droplet_id} - {$service_fields->hostname}",
                        serialize("enable_private_networking"),
                        "input",
                        true
                    );
                } else {
                    $fa = array(
                        0 => array(
                            "result" => isset($rename->message)
                                ? str_replace("Droplet", "Server", $rename->message)
                                : Language::_("Digitaloceanmodule.empty_invalid_values", true)
                        )
                    );
                    $this->Input->setErrors($fa);
                }
            } else {
                $fa = array(
                    0 => array(
                        "result" => "Private Networking can be enabled only, and cannot be disabled after the droplet is created."
                    )
                );
                $this->Input->setErrors($fa);
            }

            if ($service_fields->ipv6 === "disable") {
                $rename = $api->getPostResults(
                    "droplets/{$service_fields->droplet_id}/actions",
                    array('type' => 'enable_ipv6')
                );
                if (isset($rename->action->type) && $rename->action->type == "enable_ipv6") {
                    $this->log(
                        "Enable IPv6 For Droplet {$service_fields->droplet_id} - {$service_fields->hostname}",
                        serialize("enable_ipv6"),
                        "input",
                        true
                    );
                } else {
                    $fa = array(
                        0 => array(
                            "result" => isset($rename->message)
                                ? str_replace("Droplet", "Server", $rename->message)
                                : Language::_("Digitaloceanmodule.empty_invalid_values", true)
                        )
                    );
                    $this->Input->setErrors($fa);
                }
            } else {
                $fa = array(
                    0 => array(
                        "result" => "IPv6 can be enabled only, and cannot be disabled after the droplet is created."
                    )
                );
                $this->Input->setErrors($fa);
            }
        }

        if ($this->Input->errors()) {
            return;
        }


        return array(
            array(
                'key' => "droplet_id",
                'value' => isset($service_fields->droplet_id) ? $service_fields->droplet_id : null,
                'encrypted' => 0
            ),
            array(
                'key' => "hostname",
                'value' => isset($vars['hostname']) ? $vars['hostname'] : $service_fields->hostname,
                'encrypted' => 0
            ),
            array(
                'key' => "region",
                'value' => isset($service_fields->region) ? $service_fields->region : null,
                'encrypted' => 0
            ),
            array(
                'key' => "image",
                'value' => isset($vars['configoptions']['image'])
                    ? $vars['configoptions']['image']
                    : $service_fields->image,
                'encrypted' => 0
            ),
            array(
                'key' => "backups",
                'value' => isset($vars['configoptions']['backups'])
                    ? $vars['configoptions']['backups']
                    : $service_fields->backups,
                'encrypted' => 0
            ),
            array(
                'key' => "ipv6",
                'value' => isset($vars['configoptions']['ipv6'])
                    ? $vars['configoptions']['ipv6']
                    : $service_fields->ipv6,
                'encrypted' => 0
            ),
            array(
                'key' => "private_networking",
                'value' => isset($vars['configoptions']['private_networking'])
                    ? $vars['configoptions']['private_networking']
                    : $service_fields->private_networking,
                'encrypted' => 0
            ),
            array(
                'key' => "user_data",
                'value' => isset($service_fields->user_data) ? $service_fields->user_data : null,
                'encrypted' => 0
            ),
            array(
                'key' => "client_sshkey",
                'value' => isset($service_fields->client_sshkey) ? $service_fields->client_sshkey : null,
                'encrypted' => 1
            )
        );
    }

    public function suspendService($package, $service, $parent_package = null, $parent_service = null)
    {
        $row = $this->getModuleRow($package->module_row);
        $api = $this->getApi($row->meta->apiKey);
        $service_fields = $this->serviceFieldsToObject($service->fields);

        if ($row) {
            $service = array();
            $service['type'] = "shutdown";
            $results = $api->getPostResults("droplets/{$service_fields->droplet_id}/actions", $service);

            if (isset($results->message) && !empty($results->message)) {
                $fa = array(
                    0 => array(
                        "result" => $results->message
                    )
                );
                $this->Input->setErrors($fa[0]);
            }
        }



        return null;
    }

    public function unsuspendService($package, $service, $parent_package = null, $parent_service = null)
    {
        $row = $this->getModuleRow($package->module_row);
        $api = $this->getApi($row->meta->apiKey);
        $service_fields = $this->serviceFieldsToObject($service->fields);

        if ($row) {
            $service = array();
            $service['type'] = "power_on";
            $results = $api->getPostResults("droplets/{$service_fields->droplet_id}/actions", $service);

            if (isset($results->message) && !empty($results->message)) {
                $fa = array(
                    0 => array(
                        "result" => $results->message
                    )
                );
                $this->Input->setErrors($fa[0]);
            }
        }

        return null;
    }

    public function cancelService($package, $service, $parent_package = null, $parent_service = null)
    {
        $row = $this->getModuleRow($package->module_row);
        $api = $this->getApi($row->meta->apiKey);
        $service_fields = $this->serviceFieldsToObject($service->fields);

        if ($row) {
            $results = $api->getDeleteResults("droplets/{$service_fields->droplet_id}");
            $api->getDeleteResults("account/keys/{$service_fields->client_sshkey}");
        }

        return null;
    }

    public function validateConnection($apiKey)
    {
        $api = $this->getApi($apiKey);
        return $api->makeTestConnection();
    }

    private function getApi($apiKey)
    {
        Loader::load(dirname(__FILE__) . DS . "apis" . DS . "digitalocean_api.php");

        $api = new DigitaloceanApi($apiKey);

        return $api;
    }

    private function getRowRules(&$vars)
    {
        $rules = array(
            'name' => array(
                'valid' => array(
                    'rule' => "isEmpty",
                    'negate' => true,
                    'message' => Language::_("Digitaloceanmodule.error.row.name", true)
                )
            ),
            'apiKey' => array(
                'valid' => array(
                    'rule' => "isEmpty",
                    'negate' => true,
                    'message' => Language::_("Digitaloceanmodule.error.row.apiK_key", true)
                )
            ),
            'apiKey' => array(
                'valid_connection' => array(
                    'rule' => array(array($this, "validateConnection")),
                    'message' => Language::_("Digitaloceanmodule.error.row.connection", true)
                )
            )
        );

        return $rules;
    }
}
