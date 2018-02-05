<?php

namespace App\Roles;

class Role
{
    public function getRole($role)
    {
      if(!empty($role))
      {
          if($role == 'is_admin')
          {
              $userLevel = 9;
          }
          elseif($role == 'is_editor')
          {
              $userLevel = 8;
          }
          elseif($role == 'is_contributor')
          {
              $userLevel = 7;
          }
          elseif($role == 'is_subscriber')
          {
              $userLevel = 1;
          }
      }else{
          echo "Empty Role";
          return false;
      }
      return $userLevel;
    }

}
