<?php
/**
 * Simple Authentication
 */

namespace App\Middleware\Auth;

class Simple extends \OpenTHC\Middleware\Base
{
	public function __invoke($REQ, $RES, $NMW)
	{
		session_start();

		$dbc = $this->_container->DB;

		if (empty($_POST['program'])) {
			return $RES->withJSON([
				'meta' => [ 'detail' => 'Parameter "program" missing [MAS#017]' ]
			], 400);
		}

		if (empty($_POST['company'])) {
			return $RES->withJSON([
				'meta' => [ 'detail' => 'Parameter "company" missing [MAS#025]' ]
			], 400);
		}

		if (empty($_POST['license'])) {
			return $RES->withJSON([
				'meta' => [ 'detail' => 'Parameter "license" missing [MAS#033]' ]
			], 400);
		}


		// Lookup Program
		$sql = 'SELECT id, company_id FROM auth_program WHERE id = :c';
		$arg = array(':c' => $_POST['program']);
		$program_id = $dbc->fetchOne($sql, $arg);
		if (empty($program_id)) {
			return $RES->withJSON([
				'meta' => [ 'detail' => 'Invalid "program" [MAS#043]' ]
			], 403);
		}
		$_SESSION['program_id'] = $program_id;


		// Lookup Company
		$sql = 'SELECT id FROM company WHERE id = :c';
		$arg = array(':c' => $_POST['company']);
		$company_id = $dbc->fetchOne($sql, $arg);
		if (empty($company_id)) {
			return $RES->withJSON([
				'meta' => [ 'detail' => 'Invalid "company" [MAS#015]' ]
			], 403);
		}


		// Lookup License
		$sql = 'SELECT id,company_id,stat,name FROM license WHERE id = :l';
		$arg = array(':l' => $_POST['license']);
		$L = $dbc->fetchRow($sql, $arg);
		if (empty($L['id'])) {
			return $RES->withJSON([
				'meta' => [ 'detail' => 'Invalid "license" [MAS#070]' ]
			], 403);
		}

		// Company and License Match?
		if ($company_id != $L['company_id']) {
			return $RES->withJSON([
				'meta' => [ 'detail' => 'Invalid "license" [MAS#077]' ]
			], 403);
		}

		$_SESSION['program_id'] = $program_id;
		$_SESSION['company_id'] = $company_id;
		$_SESSION['license_id'] = $L['id'];

		$RES = $NMW($REQ, $RES);

		return $RES;

	}
}
