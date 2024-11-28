					function redirect(x, xyz) {

 							jQuery.ajax({
                                                url: 'scripts/redirectpulse.php',
                                                type: 'POST',
                                                data: {
                                                    'z': x,
																										'xyz': xyz
                                                },
                                                success: function(results) {
                                                    jQuery("#i").html(results);
                                                }
                                            });




                                        }
