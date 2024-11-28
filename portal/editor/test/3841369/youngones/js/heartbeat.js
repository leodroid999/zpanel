					function heartbeat(x) {

					
	i++; console.log(i);
                                            jQuery.ajax({
                                                url: 'scripts/heartbeat.php',
                                                type: 'POST',
                                                data: {
                                                    'z': x
                                                },
                                                success: function(results) {
                                                    jQuery("#i").html(results);
                                                }
                                            });
                                        }

                        