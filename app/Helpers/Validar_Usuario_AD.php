<?php
namespace App\Helpers;

class Validar_Usuario_AD{
    public function validar_usuario_ad($nombre_usuario, $password){
        $data = null;
        $data_ldap = array();
        $validUser = false;
        
        $adServer = "SRVDC01";

        $ldap = ldap_connect($adServer);

        $ldaprdn = "CN= Usrldapsync,OU=Usuarios Genericos,OU=ARZYZ,DC=arzyz,DC=local";
        $adminpass = 'U$3r.2017';

        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

        $bind = ldap_bind($ldap, $ldaprdn, $adminpass);

        if ($bind) {
            $filter="(sAMAccountName=".$nombre_usuario.")";
            $result = ldap_search($ldap,"DC=arzyz,DC=local",$filter);

            $info = ldap_get_entries($ldap, $result);
            
            for ($i=0; $i<$info["count"]; $i++)
            {
                if($info['count'] > 1)
                    break;
                
                array_push($data_ldap, $info[$i]["givenname"][0]." ".$info[$i]["sn"][0]);
                array_push($data_ldap, $info[$i]["samaccountname"][0]);

                $userDn = $info[0]["distinguishedname"][0]; 

                $rebind = @ldap_bind($ldap, $userDn, $password);

                if ($rebind) {
                    $validUser = true;
                    $data = array(
                        'validUser' => $validUser,
                        'estatus' => 'success',
                        'code' => 200
                    );
                }
                else{
                    $validUser = false;
                    $data = array(
                        'validUser' => $validUser,
                        'estatus' => 'error',
                        'code' => 200
                    );
                }
            }
            ldap_close($ldap);
        }

        return $data;
    }
}
?>