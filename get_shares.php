<?php
/**
 * Handles POST request and makes cURL request to get shares
 */

// Proceed only with POST request.
if ( ! empty( $_POST ) ) {
	$namespace_id = filter_var( $_POST['namespace_id'], FILTER_SANITIZE_STRING );
	$height       = filter_var( $_POST['height'], FILTER_SANITIZE_NUMBER_INT );
	if ( ! empty( $namespace_id ) && ! empty( $height ) ) {
		// Init cURL request.
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, 'http://77.120.115.144:26659/namespaced_shares/' . $namespace_id . '/height/' . $height );
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
				'message' => 'No correct data were provided',
			)
		);
	}
} else {
	echo json_encode(
		array(
			'success' => false,
			'message' => 'No request were sent',
		)
	);
}
