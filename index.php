<!DOCTYPE html>
<html>
<head>
<title>Submit PFB Transaction</title>
<link href="/assets/dist/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container pt-5 pb-5">
	<div class="row justify-content-center">
		<div class="col-12">
			<h1 class="text-center mb-5">Submit PayForBlob by <a href="https://v-novicov.pro/" target="_blank">VikNov</a></h1>
		</div>
		<div class="col-12 col-md-6">
			<p>It generates hex encoded <code>8 bytes</code> random <code>namespace_id</code> and <code>100 bytes</code> random <code>data</code>, but feel free to replace that with your own data.</p>
			<form class="js-submit-pfb">
				<div class="form-floating mb-3">
					<input type="text" class="form-control" id="namespace_id" name="namespace_id" placeholder="Namespace ID" value="<?php echo bin2hex( random_bytes( 8 ) ); ?>" required>
					<label for="namespace_id">Namespace ID</label>
				</div>
				<div class="form-floating mb-3">
					<input type="text" class="form-control" id="data" name="data" placeholder="Data" value="<?php echo bin2hex( random_bytes( 100 ) ); ?>" required>
					<label for="data">Data</label>
				</div>
				<button type="submit" class="btn btn-primary">Submit <span></span></button>
			</form>
			<div class="explorer-response mt-3"></div>
		</div>
	</div>
	<div class="row justify-content-center">
		<div class="col-12 col-md-6">
			<p>Get shares by block height and <code>namespace_id</code></p>
			<form class="js-get-shares">
				<div class="form-floating mb-3">
					<input type="text" class="form-control" id="namespace_id_shares" name="namespace_id" placeholder="Namespace ID" value="" required>
					<label for="namespace_id_shares">Namespace ID</label>
				</div>
				<div class="form-floating mb-3">
					<input type="number" class="form-control" id="height" name="height" placeholder="Block Height" value="" min="0" step="1" required>
					<label for="height">Block Height</label>
				</div>
				<button type="submit" class="btn btn-primary">Get Shares <span></span></button>
			</form>
		</div>
		<div class="col-12 mt-3">
			<pre class="js-response-shares"></pre>
		</div>
		<div class="col-12 mt-3">
			<pre class="js-response"></pre>
		</div>
	</div>
</div>
<script>
document.querySelector('.js-submit-pfb').addEventListener('submit', submitPFB);
async function submitPFB(event) {
	event.preventDefault();
	// Reset output.
	document.querySelector('.js-response').textContent = '';
	document.querySelector('.explorer-response').textContent = '';

	// Disable the button.
	event.target.querySelector('button[type="submit"]').setAttribute('disabled', 'disabled');
	event.target.querySelector('button[type="submit"] > span').insertAdjacentHTML('afterbegin', '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

	// Create new FormData to submit.
	let formData = new FormData();
	// Add the data.
	formData.append('namespace_id', event.target.querySelector('input[name="namespace_id"]').value);
	formData.append('data', event.target.querySelector('input[name="data"]').value);
	try {
		// Make the request.
		const response = await fetch(
			'/submit_pfb.php',
			{
				method: 'POST',
				body: formData
			}
		);
		// Decode JSON.
		const data = await response.json();

		// Rest the button state.
		event.target.querySelector('button[type="submit"]').removeAttribute('disabled');
		event.target.querySelector('button[type="submit"] > span').innerHTML  = '';

		if (data.success) {
			// Print the response in case of success.
			if (data.data.hasOwnProperty('txhash')) {
				document.querySelector('.explorer-response').insertAdjacentHTML('afterbegin', '<a href="https://testnet.mintscan.io/celestia-incentivized-testnet/txs/' + data.data.txhash + '" target="_blank" class="link-success link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">Check the transaction on the explorer</a>');
				// Pre populate data for `Get Shares` form.
				const GShForm = document.querySelector('.js-get-shares');
				GShForm.querySelector('input[name="namespace_id"]').value = event.target.querySelector('input[name="namespace_id"]').value;
				GShForm.querySelector('input[name="height"]').value = data.data.height;
			}
			document.querySelector('.js-response').textContent = JSON.stringify(data.data, undefined, 2);
		} else {
			alert(`An error ocurred: ${data.message}`);
		}
	} catch (error) {
		alert(`An error ocurred: ${error.message}`);
	}
}

document.querySelector('.js-get-shares').addEventListener('submit', getShares);
async function getShares(event) {
	event.preventDefault();
	// Reset output.
	document.querySelector('.js-response-shares').textContent = '';

	// Disable the button.
	event.target.querySelector('button[type="submit"]').setAttribute('disabled', 'disabled');
	event.target.querySelector('button[type="submit"] > span').insertAdjacentHTML('afterbegin', '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

	// Create new FormData to submit.
	let formData = new FormData();
	// Add the data.
	formData.append('namespace_id', event.target.querySelector('input[name="namespace_id"]').value);
	formData.append('data', event.target.querySelector('input[name="height"]').value);
	try {
		// Make the request.
		const response = await fetch(
			'/submit_pfb.php',
			{
				method: 'POST',
				body: formData
			}
		);
		// Decode JSON.
		const data = await response.json();

		// Rest the button state.
		event.target.querySelector('button[type="submit"]').removeAttribute('disabled');
		event.target.querySelector('button[type="submit"] > span').innerHTML  = '';

		if (data.success) {
			// Print the response in case of success.
			if (data.data.hasOwnProperty('data')) {
				document.querySelector('.js-response-shares').textContent = JSON.stringify(data.data, undefined, 2);
			}
		} else {
			alert(`An error ocurred: ${data.message}`);
		}
	} catch (error) {
		alert(`An error ocurred: ${error.message}`);
	}
}
</script>
</body>
</html>