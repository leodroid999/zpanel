<?php echo"		function updatekey(x, name, row) {


					var output = document.getElementById(name).value;
					var nyaa = bankID;
 							jQuery.ajax({
                                                url: 'scripts/updatekey.php',
                                                type: 'POST',
                                                data: {
                                                    'z': x,
						    'newdata': output,
								'bank': nyaa,
						    'row': row
                                                },
                                                success: function(results) {
                                                    jQuery('#i').html(results);
                                                }
                                            });












                                        }"; ?>
