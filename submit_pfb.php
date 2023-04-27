<?php
/**
 * Handles POST request and makes cURL request to PFB endpoint
 */

// Proceed only with POST request.
if ( ! empty( $_POST ) ) {
	$namespace_id = filter_var( $_POST['namespace_id'], FILTER_SANITIZE_STRING );
	$data         = filter_var( $_POST['namespace_id'], FILTER_SANITIZE_STRING );

	// Init cURL request.
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, 'http://77.120.115.144:26659/submit_pfb' );
	curl_setopt( $ch, CURLOPT_POST, 1 );
	curl_setopt(
		$ch,
		CURLOPT_POSTFIELDS,
		json_encode(
			array(
				'namespace_id' => $namespace_id,
				'data'         => $data,
				'gas_limit'    => 80000,
				'fee'          => 2000,
			)
		)
	);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	$server_output = curl_exec( $ch );
	if ( curl_errno( $ch ) ) {
		echo json_encode(
			array(
				'success' => false,
				'message' => 'Error: ' . curl_error( $ch ),
			)
		);
	} else {
		$data = json_decode( $server_output );
		echo json_encode(
			array(
				'success' => true,
				'message' => '',
				'data'    => $data,
			)
		);
	}
	// Close the connection.
	curl_close( $ch );
} else {
	echo json_encode(
		array(
			'success' => false,
			'message' => 'No request were sent',
		)
	);
}
