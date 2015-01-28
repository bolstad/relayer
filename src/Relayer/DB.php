<?php

namespace Relayer;


class DB {

	var $dbh;

	function __construct( $dsn, $login, $password, $options = array() ) {
		try {
			$this->dbh = new \PDO( $dsn, $login, $password, $options );
		}
		catch( Exception $e ) {
			throw new PDOException( 'Could not connect to database, hiding connection details.' );
		}
	}

	function pass( $query, $callback ) {
		try {
			$stmt = $this->dbh->prepare( $query, array( \PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL ) );
			$stmt->execute();
			while ( $row = $stmt->fetch( \PDO::FETCH_ASSOC, \PDO::FETCH_ORI_NEXT ) ) {
				call_user_func( $callback, $row );
			}
			$stmt = null;
		}
		catch ( PDOException $e ) {
			print $e->getMessage();
		}
	}

}
