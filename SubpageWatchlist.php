<?php
/**
 * Extension to add option to watchlist to include subpages
 * of watched page on Special:Watchlist.
 *
 * @author Brian Wolff <bawolff+sw@gmail.com>
 *
 * Copyright © Brian Wolff 2013.
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'This file is a MediaWiki extension, it is not a valid entry point' );
}


// Technically not a "new" special page, but it does modify one.
$wgExtensionCredits['specialpage'][] = array(
	'path' => __FILE__,
	'name' => 'SubpageWatchlist',
	'descriptionmsg' => 'subpagewatchlist-desc',
	'version' => 0.1,
	'url' => 'https://mediawiki.org/wiki/Extension:SubpageWatchlist',
	'author' => '[https://mediawiki.org/wiki/User:Bawolff Brian Wolff]',
);

$dir = dirname(__FILE__) . '/';

$wgExtensionMessagesFiles['SubpageWatchlist'] = $dir . 'SubpageWatchlist.i18n.php';

$wgAutoloadClasses['SubpageWatchlist'] = $dir . 'SubpageWatchlist_body.php';

$wgHooks['SpecialWatchlistFilters'][] = 'SubpageWatchlist::onSpecialWatchlistFilters';
$wgHooks['SpecialWatchlistQuery'][] = 'SubpageWatchlist::onSpecialWatchlistQuery';
