<?php
/**
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

class SubpageWatchlist {
	/**
	 * Add a new option to the special page.
	 *
	 * @param SpecialWatchlist $specialPage
	 * @param Array $customOpts List of added options
	 * @return boolean
	 */
	static public function onSpecialWatchlistFilters( $specialPage, $customOpts ) {
		$customOpts['hidesubpage'] = array(
			// FIXME: make this into a preference.
			'default' => 1, //(int) $specialPage->getUser()->getBoolOption( 'watchlisthideminor' ),
			'msg' => 'subpagewatchlist-rchidesubpage'
		);
		return false;
	}

	/**
	 * Change query if showing subpages is set.
	 *
	 * @param Array $conds Where conditions for query
	 * @param Array $tables Tables to use in query
	 * @param Array $join_conds Join conditions
	 * @param Array $fields Fields to select in the query
	 * @param Array $values Option values for special pages
	 * @throws MWException If core changes how it makes the query, and this doesn't work.
	 * @return boolean
	 */
	static public function onSpecialWatchlistQuery( $conds, $tables, $join_conds, $fields, $values ) {
		if ( !$values['hidesubpage'] ) {
			// This is a tad fragile
			if ( isset( $join_conds['watchlist'] )
				&& $join_conds['watchlist'][1][1] === 'wl_title=rc_title'
			) {
				$dbr = wfGetDB( DB_SLAVE, "watchlist" );
				$join_conds['watchlist'][1][1] = $dbr->makeList(
					array(
						'wl_title = rc_title',
						$dbr->buildConcat( array( 'wl_title', $dbr->addQuotes( '/' ) ) ) . '=' .
							'SUBSTR( rc_title, 1, CHAR_LENGTH( wl_title ) + 1 )',
					),
					LIST_OR
				);
				// BUG: This can introduce duplicates to the list. Ways of fixing this
				// * Change it to a SELECT DISTINCT. Describe suggests worse efficiency issues
				// * Add group by. In my limitted testing, group by rc_timestamp doesn't seem
				// to change the DESCRIBE, however that will cause some non-duplicates to remove
				// * Remove duplicates on PHP side after query. Kind of icky, as won't return
				// the expected number of results anymore.
				// On top of all that, no hooks in the right place to do any of those
			} else {
				throw new MWException( __METHOD__ . " Could not modify Watchlist query." );
			}
		}
		return false;
	}
}
