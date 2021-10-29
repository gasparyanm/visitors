<?php

require './services/DatabaseService.php';

class UserDetectorService {
    private string $siteUrl;
    public const EACH_VIEW_COUNT = 1;

    public function __construct(string $siteUrl)
    {
        $this->siteUrl = $siteUrl;
    }

    public function saveVisitorData()
    {
        // get info about visitor (ip, user_agent, etc.)
        $data = $this->getVisitorDefaultData();

        $db = DatabaseService::getDbInstance();
        $db->setTable('visitors');

        $visitorData = $db->getVisitorData($data['page_url'], $data['ip_address'], $data['user_agent']);

        if (is_null($visitorData)) {
            return DatabaseService::insertVisitorData($db, $data);
        }

        return DatabaseService::updateVisitorData($db, $visitorData);
    }

    private function getVisitorDefaultData(): array
    {
        return [
            'page_url' => $this->siteUrl,
            'ip_address' => self::getUserIp(),
            'user_agent' => self::getUserAgent(),
            'view_date' => DB::now(),
            'views_count' => self::EACH_VIEW_COUNT
        ];
    }

    public static function getUserIp()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    public static function getUserAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }
}
?>