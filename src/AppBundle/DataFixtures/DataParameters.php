<?php


namespace AppBundle\DataFixtures;

/**
 * Class DataParameters
 * @package AppBundle\DataFixtures
 */
class DataParameters
{
    /**
     * Roles parameters
     */
    const NB_SUPER_ADMIN                = 1;
    const NB_ADMIN                      = 5;
    const NB_PROJECT_RESP               = 10;
    const NB_TECHNICIAN                 = 20;
    const NB_COMMERCIAL                 = 20;
    const NB_FINAL_CLIENT               = 50;

    const NB_TOTAL_USERS                = self::NB_SUPER_ADMIN+self::NB_ADMIN+self::NB_PROJECT_RESP+self::NB_TECHNICIAN+self::NB_COMMERCIAL+self::NB_FINAL_CLIENT;
    const NB_USER_PROFILE               = self::NB_TOTAL_USERS;

    /**
     * Others
     */
    const NB_USER_TECHNICAL_EVOLUTION   = 2500;

    const NB_COMPANY                    = 30;
    const NB_PRODUCT                    = 2;
    const NB_CATEGORY                   = 9;
    const NB_TECHNICAL_EVOLUTION        = 50;
    const NB_DOCUMENTATION              = 25;
    const NB_FAQ                        = 20;

    /**
     * @return mixed
     */
    public static function getRandomUser()
    {
        $users = [
            0 => 'user_super_admin_id_' . mt_rand(0, self::NB_SUPER_ADMIN - 1),
            1 => 'user_admin_id_' . mt_rand(0, self::NB_ADMIN - 1),
            2 => 'user_project_resp_id_' . mt_rand(0, self::NB_PROJECT_RESP - 1),
            3 => 'user_technician_id_' . mt_rand(0, self::NB_TECHNICIAN - 1),
            4 => 'user_commercial_id_' . mt_rand(0, self::NB_COMMERCIAL - 1),
            5 => 'user_final_client_id_' . mt_rand(0, self::NB_FINAL_CLIENT - 1),
        ];

        return $users[mt_rand(0, 5)];
    }
}