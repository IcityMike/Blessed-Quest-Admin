<?php

	/** Routes without login ***/
	
	
	Route::prefix('refferal-partner')->namespace('Refferal')->middleware(['auth:refferal','revalidate'])->group(function () {

	});
?>