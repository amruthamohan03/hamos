class MenuModel extends CoreModel
{
    public function getMenu()
    {
        $sql = "SELECT * FROM menu_master_t WHERE display='Y' ORDER BY menu_level, menu_id, id";
        $this->db->query($sql);
        $items = $this->db->resultSet(); // returns array of stdClass objects

        // Organize menu by parent-child hierarchy
        $menu = [];
        $lookup = [];

        foreach ($items as $item) {
            $item->submenu = []; // prepare submenu array
            $lookup[$item->id] = $item;

            if ($item->menu_level == 0) {
                $menu[] = $item;
            } elseif (isset($lookup[$item->menu_id])) {
                $lookup[$item->menu_id]->submenu[] = $item;
            }
        }

        return $menu;
    }
}
