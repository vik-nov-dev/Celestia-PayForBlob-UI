# Celestia-PayForBlob-UI
A tiny and simple PHP UI for submitting Celestia PayForBlob transactions

---

Current preview is available at [http://77.120.115.144/](http://77.120.115.144/).

Feel free to fork the repo.

It's workable instantly, just replace cURL URLs in `get_shares.php` and `submit_pfb.php` files.

---

Bootstrap framework was used to build the UI.
`index.php` contains the HTML and vanilla JS that sends background requsts:

1. To `submit_pfb.php` file to submit PayFoBlob transaction.
2. To `get_shares.php` to get the shares.

That files then perform cURL requests with PHP. It allows to get around the CORS policy.

On each page load, a random `namespace_id` and `data` are generated, but user can replace them with own data.

Once PayForBlob transaction is performed, the data is prepopulated to the Get Shares form,
but the form can be used with arbitrary data exclusively.
