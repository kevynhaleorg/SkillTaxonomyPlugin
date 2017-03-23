<?php
/**
 * @package KySkillTaxonomy
 */
/*
Plugin Name: Ky Skill Taxonomy
Plugin URI: https://kydeveloper.com/
Description: Plugin to add custom taxonomies and fields to handle the skills page on the portfolio website. Tracks skills used in a job, project, and blog and reflects that in a count per skill. Provides a rest API to retrieve the data.
Version: 1.0
Author: Kevyn Hale
Author URI: kydeveloper.com
License: GPLv2 or later
Text Domain: kydeveloper
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2005-2015 Automattic, Inc.
*/

// Add Custom Skill Taxonomy

include("skill-meta.php");

$KY_SKILL_META = new KY_SKILL_META();
$KY_SKILL_META -> init();








?>