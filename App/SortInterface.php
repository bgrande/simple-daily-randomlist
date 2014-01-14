<?php

namespace dailySort\App;

interface SortInterface 
{
    public function getSortedList();

    public function resetList();
}