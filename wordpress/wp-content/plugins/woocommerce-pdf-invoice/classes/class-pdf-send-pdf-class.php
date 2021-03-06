<?php

		// include DomPDF autoloader
        require_once ( PDFPLUGINPATH . "lib/dompdf/autoload.inc.php" );

        // reference the Dompdf namespaces
		use Dompdf\Dompdf;
		use Dompdf\Options;

        class WC_send_pdf {

            public function __construct() {

            	$this->wc_version = get_option( 'woocommerce_version' );
				
				/**
				 * Check the email being sent and attach a PDF if it's the right one
				 */
				add_filter( 'woocommerce_email_attachments' , array( $this,'pdf_attachment' ) ,10, 3 );

            }

            /**
			 * Check the email being sent and attach a PDF if it's the right one
             */
		 	function pdf_attachment( $attachment = NULL, $id = NULL, $order = NULL ) {
				
				$woocommerce_pdf_invoice_options = get_option( 'woocommerce_pdf_invoice_settings' );
			 	
				// Attach to the Completed Order email and the Completed Renewal Order email (Subscriptions)
				if ( $id == 'customer_completed_order' || $id == 'customer_completed_renewal_order' ) {			
					return $this->get_woocommerce_invoice( $order );
			 	}
				
				// Attach to the other emails
				if ( !empty($woocommerce_pdf_invoice_options['attach_multiple']) && in_array( $id, $woocommerce_pdf_invoice_options['attach_multiple'] ) ) {
					
					if ( $order->status == 'processing' && $id == 'customer_processing_order' ) {
						return $this->get_woocommerce_invoice( $order );
					}
						
					if ( $id == 'new_order' || $id == 'customer_invoice' ) {
						return $this->get_woocommerce_invoice( $order );
					}
					
			 	}
				
				// Attach to emails for on hold orders
				if ( !empty($woocommerce_pdf_invoice_options['attach_multiple'])  ) {
					
					if ( $order->status == 'on-hold' && $id == 'customer_processing_order' && in_array( 'on-hold', $woocommerce_pdf_invoice_options['attach_multiple'] )  ) {
						return $this->get_woocommerce_invoice( $order );
					}
						
				}
				
		 	} // pdf_attachment

			Public Static function get_woocommerce_invoice( $order = NULL, $stream = NULL ) {
				global $woocommerce;

				// Set the temp directory
				$pdftemp = sys_get_temp_dir();

				$upload_dir =  wp_upload_dir();
                if ( file_exists( $upload_dir['basedir'] . '/woocommerce_pdf_invoice/index.html' ) ) {
    				$pdftemp = $upload_dir['basedir'] . '/woocommerce_pdf_invoice/';

    				// Windows hosting check
					$pos = strpos( $pdftemp, ":\\" );
					if ( $pos === false ) {

					} else {
    					$pdftemp = str_replace('/', '\\', $pdftemp );
					}

                }

				$pdf = new WC_send_pdf();
				
				$woocommerce_pdf_invoice_options = get_option('woocommerce_pdf_invoice_settings');

				// And now for the user variables, paper size and the like.
				$papersize 			= $woocommerce_pdf_invoice_options['paper_size']; 			// Currently A4 or Letter
				$paperorientation 	= $woocommerce_pdf_invoice_options['paper_orientation']; 	// Portrait or Landscape
				$customlogo			= '';														// No logo? No problem, we'll just use get_bloginfo('name')
				$footertext			= '';														// This is the legal stuff that you should be including everywhere!

				if( !isset($woocommerce_pdf_invoice_options['enable_remote']) || $woocommerce_pdf_invoice_options['enable_remote'] == 'false' ) {
					$pdfremoteimages	= false;
				} else {
					$pdfremoteimages	= true;
				}

				/**
				 * create the file name based on the settings
				 *
				 * Allowed variables
				 *
				 * companyname
				 * invoicedate
				 * invoicenumber
				 * year
				 */
				$filename	= $woocommerce_pdf_invoice_options['pdf_filename'];
				if ( $filename == '' ) {

					$filename	= get_bloginfo('name') . '-' . $order->id;

				} else {

					$replace 	= array( ' ', "/", "'",'"' );

					$filename	= str_replace( '{{company}}',	$woocommerce_pdf_invoice_options['pdf_company_name'] , $filename );
					$filename	= str_replace( '{{invoicedate}}' ,	$pdf->get_woocommerce_pdf_date( $order->id,'completed' ) , $filename );
					$filename	= str_replace( '{{invoicenumber}}',	( $pdf->get_woocommerce_pdf_invoice_num( $order->id ) ? $pdf->get_woocommerce_pdf_invoice_num( $order->id ) : $order->id ) , $filename );
					$filename	= str_replace( '{{month}}',	date('F') , $filename );
					$filename	= str_replace( '{{mon}}',	date('M') , $filename );
					$filename	= str_replace( '{{year}}',	date('Y') , $filename );
					$filename	= str_replace( $replace, '-' , $filename );
					
				}

				$filename = apply_filters( 'pdf_output_filename', $filename, $order->id );

				$messagetext  = '';
				$messagetext .= $pdf->get_woocommerce_invoice_content( $order->id );
					
				if ( $stream && 
					( !isset($woocommerce_pdf_invoice_options['pdf_termsid']) || $woocommerce_pdf_invoice_options['pdf_termsid'] == 0 ) && 
					( !isset($woocommerce_pdf_invoice_options['pdf_creation']) || $woocommerce_pdf_invoice_options['pdf_creation'] == 'standard' )
				) {
					// Start the PDF Generator for the invoice
					@ob_clean();

					// Set Option for remote images
					$options = new Options();
					$options->set([
						'isRemoteEnabled' => $pdfremoteimages,
					]);

					$dompdf = new DOMPDF();
					$dompdf->setOptions($options);
					$dompdf->load_html( $messagetext );
					$dompdf->set_paper( $papersize, $paperorientation );
					$dompdf->render();
						
					// Output the PDF for download
					return $dompdf->stream($filename);
					exit;
						
				} elseif ( 
					( isset($woocommerce_pdf_invoice_options['pdf_termsid']) && $woocommerce_pdf_invoice_options['pdf_termsid'] != 0 ) || 
					( isset($woocommerce_pdf_invoice_options['pdf_creation']) && $woocommerce_pdf_invoice_options['pdf_creation'] == 'file' )
				) {
					/**
					 * This section deals with sending / generating a PDF Invoice that will include a Terms and Conditions page
					 * Uses PDF Merge library
					 *
					 * REPLACE 'file' WITH 'browser', 'download', 'string', or 'file' for output options
					 * You do not need to give a file path for browser, string, or download - just the name.
					 */
					
					// Add PDF extension 
					if (strpos($filename, '.pdf') === false) {
						$filename =  $filename . '.pdf';
					}
						 
					// Set Option for remote images
					$options = new Options();
					$options->set([
						'isRemoteEnabled' => $pdfremoteimages,
					]);

					$dompdf = new DOMPDF();
					$dompdf->setOptions($options);
					$dompdf->load_html( $messagetext );
					$dompdf->set_paper( $papersize, $paperorientation );
					$dompdf->render();
						
					$invattachments = $pdftemp . '/inv' . $filename;
						
					// Write the PDF to the TMP directory		
					file_put_contents( $invattachments, $dompdf->output() );
						
					@ob_clean();

					if ( !class_exists('PDFMerger') ) {
						include ( PDFPLUGINPATH . 'lib/PDFMerger/PDFMerger.php' );
					}

					if ( isset($woocommerce_pdf_invoice_options['pdf_termsid']) && $woocommerce_pdf_invoice_options['pdf_termsid'] != 0 ) {

						// Start the PDF Generator for the terms
						$dompdf = new Dompdf();
						$dompdf->load_html( $pdf->get_woocommerce_invoice_terms( $woocommerce_pdf_invoice_options['pdf_termsid'] ) );
						$dompdf->set_paper( $papersize, $paperorientation );
						$dompdf->render();
						
						$termsattachments = $pdftemp . '/terms-' . $filename;
						
						// Write the PDF to the TMP directory		
						file_put_contents( $termsattachments, $dompdf->output() );
					
						$pdf = new PDFMerger;
						
						if ( $stream ) {
							$pdf->addPDF( $invattachments, 'all' )
								->addPDF( $termsattachments, 'all' )
								->merge( 'download', $filename );
								exit;
						} else {
							$pdf->addPDF( $invattachments, 'all' )
								->addPDF( $termsattachments, 'all' )
								->merge( 'file', $pdftemp . '/' . $filename );
						}

					} else {
					
						$pdf = new PDFMerger;

						if ( $stream ) {
							$pdf->addPDF( $invattachments, 'all' )
								->merge( 'download', $filename );
								exit;
						} else {
							$pdf->addPDF( $invattachments, 'all' )
								->merge( 'file', $pdftemp . '/' . $filename );
						}

					}
						
					// Send the file name and location to the Email
					// return 	array( $invattachments, $termsattachments );
					return ( $pdftemp . '/' . $filename );
					exit;
											
				} else {
					// Add PDF extension 
					if (strpos($filename, '.pdf') === false) {
						$filename =  $filename . '.pdf';
					}

					@ob_clean();
					// Set Option for remoate images
					$options = new Options();
					$options->set([
						'isRemoteEnabled' => $pdfremoteimages,
					]);

					$dompdf = new DOMPDF();
					$dompdf->setOptions($options);
					$dompdf->load_html( $messagetext );
					$dompdf->set_paper( $papersize, $paperorientation );
					$dompdf->render();
					
					$attachments = $pdftemp . '/' . $filename;
					
					// Write the PDF to the TMP directory		
					file_put_contents( $attachments, $dompdf->output() );
		
					// Send the file name and location to the Email
					return 	$attachments;
					exit;
						
				}

			}

			/**
			 * Get the PDF order details in a table
			 * @param  [type] $order_id 
			 * @return [type]           
			 */
			function get_woocommerce_pdf_order_details( $order_id ) {
				global $woocommerce;
				$order 	 = new WC_Order( $order_id );
							
				$pdflines  = '<table width="100%">';
				$pdflines .= '<tbody>';
				
				if ( sizeof( $order->get_items() ) > 0 ) : 

					foreach ( $order->get_items() as $item ) {
						
						if ( $item['qty'] ) {
							
							$line = '';
							// $item_loop++;

							$_product 	= $order->get_product_from_item( $item );
							$item_name 	= $item['name'];

							if ( $this->wc_version < '2.4' ) {
								// Deprecated in WC 2.4
								$item_meta 	= new WC_Order_Item_Meta( $item['item_meta'] );

							} else {

								$item_meta  = new WC_Order_Item_Meta( $item, $_product );

							}

							if ( $meta = $item_meta->display( true, true ) ) {
								$meta_output	 = apply_filters( 'pdf_invoice_meta_output', ' ( ' . $meta . ' ) ' );
								$item_name 		.= $meta_output;
							}
							
							$line = 	'<tr class="border-gray">' .
										'<td valign="top" width="50%">' .  $item_name . '</td>' .
										'<td valign="top" width="16%" align="left">'  .  wc_price( $item['line_subtotal'] / $item['qty'], array( 'currency' => $order->get_order_currency() ) ) . '</td>' .	
									    '<td valign="top" width="16%" align="left">' . $item['qty'] . '</td>' .	
										'<td valign="top" width="17%" align="left">' .  wc_price( $item['line_subtotal'] + $item['line_subtotal_tax'], array( 'currency' => $order->get_order_currency() ) ). '</td>' .
										'</tr>';
							
							$pdflines .= $line;
						}
					}
			
				endif;

				$pdflines .=	'</tbody>';
				$pdflines .=	'</table>';
				
				$pdf 	= apply_filters( 'pdf_template_line_output', $pdflines, $order_id );
				return $pdf;
			}

			/**
			 * Get the Invoice Number
			 * @param  [type] $order_id [description]
			 * @return [type]           [description]
			 */
			function get_woocommerce_pdf_invoice_num( $order_id ) {
				global $woocommerce;
		
				if ( $order_id ) :
					$invnum = esc_html( get_post_meta( $order_id, '_invoice_number_display', true ) );
				else :
					$invnum = ''; 
				endif;

				return $invnum;
			}
	
			/** 
			 * Get the invoice date
			 * @param  [type] $order_id [description]
			 * @param  [type] $usedate  [description]
			 * @return [type]           [description]
			 */
			public static function get_woocommerce_pdf_date( $order_id, $usedate ) {
				global $woocommerce;
				
				$woocommerce_pdf_invoice_options = get_option( 'woocommerce_pdf_invoice_settings' );
				$date_format = $woocommerce_pdf_invoice_options['pdf_date_format'];
				
				$order 	 = new WC_Order( $order_id );
		
				if ( $usedate == 'completed' ) :
					$date = esc_html( get_post_meta( $order_id, '_invoice_date', true ) );
				else :
					$date = esc_html( $order->order_date );
				endif;
				
				if ( !isset( $date_format ) || $date_format == '' ) :
					$date_format = "j F, Y";
				endif;
				
				if ( $date ) :
					return date_i18n( $date_format, strtotime( $date ) );
				else :
					return '';
				endif;
		
			}
			
			/**
			 * Get the order notes for the template
			 */			
			function get_pdf_order_note( $order_id ) {
				global $woocommerce;
				$woocommerce_pdf_invoice_options = get_option( 'woocommerce_pdf_invoice_settings' );
				
				if (!$order_id) return;	
				$order = new WC_Order( $order_id );
				$output = '';
				
				if( $order->customer_note ) {
					$output = '<h3>' . __('Note:', 'woocommerce-pdf-invoice') . '</h3>' . wpautop( wptexturize( $order->customer_note ) );
					$output = apply_filters( 'pdf_template_order_notes' , $output, $order_id );
				}
				return $output;
					
			}
			
			/**
			 * Get the order subtotal for the template
			 */
			function get_pdf_order_subtotal( $order_id ) {
				global $woocommerce;
				$woocommerce_pdf_invoice_options = get_option( 'woocommerce_pdf_invoice_settings' );
				
				if (!$order_id) return;	
				$order = new WC_Order( $order_id );
				$output = '';

				$output = 	'<tr class="border-gray">' .
							'<td align="left" width="83%">' .
							'<strong>' . __('Subtotal', 'woocommerce-pdf-invoice') . '</strong></td>' .
							'<td align="left" width="17%"><strong>' . $order->get_subtotal_to_display() . '</strong></td>' .
							'</tr>' ;
				$output = apply_filters( 'pdf_template_order_subtotal' , $output, $order_id );
				return $output;
			}
			
			/**
			 * Get the order shipping total for the template
			 */
			function get_pdf_order_shipping( $order_id ) {
				global $woocommerce;
				$woocommerce_pdf_invoice_options = get_option( 'woocommerce_pdf_invoice_settings' );
				
				if (!$order_id) return;	
				$order = new WC_Order( $order_id );
				$output = '';
				
				$output = 	'<tr class="border-gray">' .
							'<td width="17%" align="left">' .__('Frete', 'woocommerce-pdf-invoice') .'</td>' .
							'<td width="83%" align="left">' . $order->get_shipping_to_display() .'</td>' .
							'</tr>' ;
				
				$output = apply_filters( 'pdf_template_order_shipping' , $output, $order_id );
				return $output;
			}

			/**
			 * Show coupons used
			 */
			function pdf_coupons_used( $order_id ) {
				global $woocommerce;

				if (!$order_id) return;	
				$order = new WC_Order( $order_id );

				$return_coupon = '';

				if( $order->get_used_coupons() ) {
					
					$coupons_count = count( $order->get_used_coupons() );
					
					$i = 1;
					$coupons_list = '';
					foreach( $order->get_used_coupons() as $coupon) {
						
						$coupons_list .= $coupon;
						if( $i < $coupons_count )
							$coupons_list .= ', ';
						
						$i++;
					}

					$return_coupon .= '<br /><strong>' . __('Coupons used', 'woocommerce-pdf-invoice') . ' (' . $coupons_count . ') :</strong>' . $coupons_list;
				
				} // endif get_used_coupons

				return $return_coupon;

			}
			
			/**
			 * Get the order discount for the template
			 */
			function get_pdf_order_discount( $order_id ) {
				global $woocommerce;
				$woocommerce_pdf_invoice_options = get_option( 'woocommerce_pdf_invoice_settings' );
				
				if (!$order_id) return;	
				$order = new WC_Order( $order_id );
				$output = '';
				
				if ( $order->order_discount > 0 ) {
					$output .=  '<tr>' .
								'<td  width="83%" align="left" valign="top">' .
								'<strong>' . esc_html__('Discount', 'woocommerce-pdf-invoice') . '</strong>' . $this->pdf_coupons_used( $order_id ) . '</td>' .
								'<td align="right" valign="top" width="17%"><strong>' . woocommerce_price( $order->order_discount ) . '</strong></td>' .
								'</tr>' ;
				}

				if ( $order->cart_discount > 0 ) {

					$output .=  '<tr>' .
								'<td align="right" valign="top">' .
								'<strong>' . esc_html__('Discount', 'woocommerce-pdf-invoice') . '</strong>' . $this->pdf_coupons_used( $order_id ) . '</td>' .
								'<td align="right" valign="top"><strong>' . woocommerce_price( $order->cart_discount ) . '</strong></td>' .
								'</tr>' ;

				}
				
				$output = apply_filters( 'pdf_template_order_discount' , $output, $order_id );
				return $output;
			}
			
			/**
			 * Get the tax for the template
			 */
			function get_pdf_order_tax( $order_id ) {
				global $woocommerce;
				$woocommerce_pdf_invoice_options = get_option( 'woocommerce_pdf_invoice_settings' );
				
				if (!$order_id) return;	
				$order = new WC_Order( $order_id );
				$output = '';

				if ( $order->get_total_tax()>0 ) {

					$tax_items = $order->get_tax_totals();
				
					if ( count( $tax_items ) > 1 ) {

						foreach ( $tax_items as $tax_item ) {
							$output .=  '<tr>' .
										'<td align="right">' . esc_html( $tax_item->label ) . '</td>' .
										'<td align="right">' . woocommerce_price( $tax_item->amount ) . '</td>' .
										'</tr>' ;
						}

						$output .=  '<tr>' .
									'<td align="right">' . __('Total Tax', 'woocommerce-pdf-invoice') . '</td>' .
									'<td align="right">' . woocommerce_price( $order->get_total_tax() ) . '</td>' .
									'</tr>' ;

					} else {

						foreach ( $tax_items as $tax_item ) {

							$output .=  '<tr>' .
										'<td align="right">' . esc_html( $tax_item->label ) . '</td>' .
										'<td align="right">' . woocommerce_price( $tax_item->amount ) . '</td>' .
										'</tr>' ;
						}

					}


				}

				$output = apply_filters( 'pdf_template_order_tax' , $output, $order_id );
				return $output;

			}
			
			/**
			 * [get_pdf_order_total description]
			 * @param  [type] $order_id [description]
			 * @return [type]           [description]
			 */
			function get_pdf_order_total( $order_id ) {
				global $woocommerce;
				$woocommerce_pdf_invoice_options = get_option( 'woocommerce_pdf_invoice_settings' );
				
				if (!$order_id) return;	
				$order = new WC_Order( $order_id );

				$output =  	'<tr>' .
							'<td  width="83%" align="left">' .
							'<strong>' . __('Total', 'woocommerce-pdf-invoice') . '</strong></td>' .
							'<td  width="17%" align="left"><strong>' . woocommerce_price( $order->order_total ) . '</strong></td>' .
							'</tr>' ;
				$output = apply_filters( 'pdf_template_order_total' , $output, $order_id );
				return $output;
			}

			/**
			 * [get_pdf_order_totals description]
			 * New for Version 1.3.0, replaces several functions with one looped function
			 * @param  [type] $order_id [description]
			 * @return [type]           [description]
			 */
			function get_pdf_order_totals( $order_id ) {
				global $woocommerce;

				if (!$order_id) return;	
				$order = new WC_Order( $order_id );

				$order_item_totals = $order->get_order_item_totals();

				unset( $order_item_totals['payment_method'] );

				$output = '';

				foreach ( $order_item_totals as $order_item_total ) {

					$output .=  '<tr class="border-gray">' .
								'<td width="83%" align="left">' .
								'<strong>' . $order_item_total['label'] . '</strong></td>' .
								'<td width="17%" align="left"><strong>' . $order_item_total['value'] . '</strong></td>' .
								'</tr>' ;

				}

				if( $order->get_total_refunded() > 0 ) {

					$output .=  '<tr class="border-gray">' .
								'<td width="83%" align="left">' .
								'<strong>Amount Refunded:</strong></td>' .
								'<td width="83%" align="left"><strong>' . wc_price( $order->get_total_refunded(), array( 'currency' => $order->get_order_currency() ) ) . '</strong></td>' .
								'</tr>' ;
								
				}

				$output = apply_filters( 'pdf_template_order_totals' , $output, $order_id );
				return $output;

			}
			
			/**
			 * [get_woocommerce_invoice_content description]
			 * @param  [type] $order_id [description]
			 * @return [type]           [description]
			 */
			function get_woocommerce_invoice_content( $order_id ) {
				global $woocommerce;

				// WPML
				do_action( 'before_invoice_content', $order_id );

				$woocommerce_pdf_invoice_options = get_option( 'woocommerce_pdf_invoice_settings' );
				
				if (!$order_id) return;	
				$order 			   = new WC_Order( $order_id );

				// Check if the order has an invoice
				$invoice_number_display = get_post_meta( $order_id, '_invoice_number_display', true );

				// Use the stored company info.
				$pdfcompanyname    = get_post_meta( $order_id,'_pdf_company_name',TRUE );
				$pdfcompanydetails = nl2br(get_post_meta( $order_id,'_pdf_company_details',TRUE ));
				$pdfregisteredname = get_post_meta( $order_id,'_pdf_registered_name',TRUE );
				$pdfregaddress	   = get_post_meta( $order_id,'_pdf_registered_address',TRUE );
				$pdfcompanynumber  = get_post_meta( $order_id,'_pdf_company_number',TRUE );
				$pdftaxnumber 	   = get_post_meta( $order_id,'_pdf_tax_number',TRUE );

				if ( !isset( $pdfcompanyname ) || $pdfcompanyname == '' ) {
					$pdfcompanyname    = __( $woocommerce_pdf_invoice_options['pdf_company_name'], 'woocommerce-pdf-invoice' );
				}

				if ( !isset( $pdfcompanydetails ) || $pdfcompanydetails == '' ) {
					$pdfcompanydetails = nl2br( $woocommerce_pdf_invoice_options['pdf_company_details'] );
				}
				if ( !isset( $pdfregisteredname ) || $pdfregisteredname == '' ) {
					$pdfregisteredname = $woocommerce_pdf_invoice_options['pdf_registered_name'];
				}
				if ( !isset( $pdfregaddress ) || $pdfregaddress == '' ) {
					$pdfregaddress	   = $woocommerce_pdf_invoice_options['pdf_registered_address'];
				}
				if ( !isset( $pdfcompanynumber ) || $pdfcompanynumber == '' ) {
					$pdfcompanynumber  = $woocommerce_pdf_invoice_options['pdf_company_number'];
				}
				if ( !isset( $pdftaxnumber ) || $pdftaxnumber == '' ) {
					$pdftaxnumber 	   = $woocommerce_pdf_invoice_options['pdf_tax_number'];
				}


				$pdflogo 		   = $woocommerce_pdf_invoice_options['logo_file'];

				if ( $pdflogo ) :
					$pdflogo = str_replace( site_url(), ABSPATH, $pdflogo );
					$logo = '<img src="' . $pdflogo . '" alt="' . get_bloginfo('name') . '" />';				
				else :
					$logo = '<h1>' . get_bloginfo('name') . '</h1>';	
				endif;
		
				/**
				 * Look for the Sequential Order Numbers Pro / Sequential Order Numbers order number and use it if it's there
				 */
				$output_order_num = $order->get_order_number();

				if( !is_admin() ) {
					include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
				}

				// Sequential Order Numbers
				if ( get_post_meta( $order->id,'_order_number',TRUE ) && class_exists( 'WC_Seq_Order_Number' ) ) :
					$output_order_num = get_post_meta( $order_id,'_order_number',TRUE );
				endif;

				// Sequential Order Numbers Pro
				if ( get_post_meta( $order->id,'_order_number_formatted',TRUE ) && class_exists( 'WC_Seq_Order_Number_Pro' ) ) :
					$output_order_num = get_post_meta( $order_id,'_order_number_formatted',TRUE );
				endif;

				$headers =  '<table class="shop_table orderdetails" width="100%">' . 
							'<thead>' .
							'<tr>' .
							'<th width="50%" valign="top" align="left">'  . esc_html__( 'Produto', 'woocommerce-pdf-invoice' ) 		. '</th>' .						
							'<th width="16%" valign="top" align="left">'  . esc_html__( 'Preço', 'woocommerce-pdf-invoice' ) 	. '</th>' .
							'<th width="16%" valign="top" align="left">'  . esc_html__( 'Quantidade', 'woocommerce-pdf-invoice' ) 	. '</th>' .
							'<th width="17%" valign="top" align="left">'  . esc_html__( 'Total', 'woocommerce-pdf-invoice' ) 	. '</th>' .
							'</tr>' .
							'</thead>' .
							'</table>';

				// Buffer
				ob_start();
				
				// load_template( $pdftemplate, false );
				require( $this->get_pdf_template( 'template.php' ) );

				// Get contents
				$content = ob_get_clean();

				/**
				 * Notify when the PDF is about to be generated
				 *
				 * Added for Currency Switcher for WooCommerce
				 */
				do_action( 'woocommerce_pdf_invoice_before_pdf_content', $order );
		
				// REPLACE ALL TEMPLATE TAGS WITH REAL CONTENT
				$content = str_replace(	'[[PDFLOGO]]', 					$logo, 			 	$content );
				$content = str_replace(	'[[PDFCOMPANYNAME]]', 			$pdfcompanyname, 	$content );
				$content = str_replace(	'[[PDFCOMPANYDETAILS]]', 		$pdfcompanydetails, $content );
				$content = str_replace(	'[[PDFREGISTEREDNAME]]', 		$pdfregisteredname, $content );
				$content = str_replace(	'[[PDFREGISTEREDADDRESS]]', 	$pdfregaddress, 	$content );
				$content = str_replace(	'[[PDFCOMPANYNUMBER]]', 		$pdfcompanynumber, 	$content );
				$content = str_replace(	'[[PDFTAXNUMBER]]', 			$pdftaxnumber, 		$content );
		
				$content = str_replace(	'[[PDFINVOICENUM]]', 			$this->get_woocommerce_pdf_invoice_num( $order_id ),		$content );
				$content = str_replace(	'[[PDFORDERENUM]]', 			$output_order_num, 									  		$content );
				$content = str_replace(	'[[PDFINVOICEDATE]]', 			$this->get_woocommerce_pdf_date( $order_id,'completed' ), 	$content );
				$content = str_replace(	'[[PDFORDERDATE]]', 			$this->get_woocommerce_pdf_date( $order_id,'ordered' ), 	$content );
		
				$content = str_replace(	'[[PDFBILLINGADDRESS]]', 		$order->get_formatted_billing_address(),  					$content );
				$content = str_replace(	'[[PDFBILLINGTEL]]', 			get_post_meta( $order_id,'_billing_phone',TRUE ), 	  		$content );
				$content = str_replace(	'[[PDFBILLINGEMAIL]]', 			get_post_meta( $order_id,'_billing_email',TRUE ), 			$content );

				$content = str_replace(	'[[PDFBILLINGNAMEFIRSTADDRESS]]', 	get_post_meta( $order_id,'_billing_first_name',TRUE ), 			$content );
					$content = str_replace(	'[[PDFBILLINGNAMELASTADDRESS]]', 	get_post_meta( $order_id,'_billing_last_name',TRUE ), 			$content );
				$content = str_replace(	'[[PDFSHIPPINGADDRESS]]', 		$order->get_formatted_shipping_address(), 					$content );
				$content = str_replace(	'[[PDFINVOICEPAYMENTMETHOD]]',	ucwords($order->payment_method_title), 						$content );
				
				$content = str_replace(	'[[ORDERINFOHEADER]]',			apply_filters( 'pdf_template_table_headings', $headers ), 	$content );
				$content = str_replace(	'[[ORDERINFO]]', 				$this->get_woocommerce_pdf_order_details( $order_id ), 	  	$content );
			
				$content = str_replace(	'[[PDFORDERNOTES]]', 			$this->get_pdf_order_note( $order_id ), 	  				$content );
				
				// 1.2.16			
				$content = str_replace(	'[[PDFORDERSUBTOTAL]]', 		$this->get_pdf_order_subtotal( $order_id ), 	  			$content );
				$content = str_replace(	'[[PDFORDERSHIPPING]]', 		$this->get_pdf_order_shipping( $order_id ), 	  			$content );
				$content = str_replace(	'[[PDFORDERDISCOUNT]]', 		$this->get_pdf_order_discount( $order_id ), 	  			$content );				
				$content = str_replace(	'[[PDFORDERTAX]]', 				$this->get_pdf_order_tax( $order_id ), 	  					$content );
				$content = str_replace(	'[[PDFORDERTOTAL]]', 			$this->get_pdf_order_total( $order_id ), 	  				$content );

				// 1.3.0
				$content = str_replace(	'[[PDFORDERTOTALS]]', 			$this->get_pdf_order_totals( $order_id ), 	  				$content );
				
				
				// Support for EU VAT Number Extension
				if ( get_post_meta( $order_id,'VAT Number',TRUE ) ) {
					$content = str_replace(	'[[PDFBILLINGVATNUMBER]]', '<br />' . __( 'VAT Number : ', 'woocommerce-pdf-invoice' ) . get_post_meta( $order_id,'VAT Number',TRUE ), $content );
				} elseif ( get_post_meta( $order_id,'vat_number',TRUE ) ) {	
					$content = str_replace(	'[[PDFBILLINGVATNUMBER]]', '<br />' . __( 'VAT Number : ', 'woocommerce-pdf-invoice' ) . get_post_meta( $order_id,'vat_number',TRUE ), $content );
				} else {
					$content = str_replace(	'[[PDFBILLINGVATNUMBER]]', '', $content );	
				}
				
				$content = apply_filters( 'pdf_content_additional_content' , $content , $order_id );

				// WPML
				global $current_language;

				do_action( 'after_invoice_content', $current_language ); 
		
				return $content;
			}
			
			/**
			 * [get_woocommerce_invoice_terms description]
			 * @param  integer $page_id [description]
			 * @return [type]           [description]
			 */
			function get_woocommerce_invoice_terms( $page_id = 0 ) {
				global $woocommerce;
				$woocommerce_pdf_invoice_options = get_option( 'woocommerce_pdf_invoice_settings' );
				
				$pdfregisteredname = $woocommerce_pdf_invoice_options['pdf_registered_name'];
				$pdfregaddress	   = $woocommerce_pdf_invoice_options['pdf_registered_address'];
				$pdfcompanynumber  = $woocommerce_pdf_invoice_options['pdf_company_number'];
				$pdftaxnumber 	   = $woocommerce_pdf_invoice_options['pdf_tax_number'];
				
				if ( $page_id == 0 ) 
					return;
				
				/** 
				 * Get terms template
				 * 
				 * Put your customized template in 
				 * wp-content/themes/YOUR_THEME/pdf_templates/terms-template.php
				 */
				$termstemplate 	= $this->get_pdf_template( 'terms-template.php' );
				
				// Buffer
				ob_start();
				
				require( $termstemplate );
	
				// Get contents
				$content = ob_get_clean();

				$id		 = $page_id; 
				$post 	 = get_post( $id );  
				$terms 	 = apply_filters( 'the_content', $post->post_content ); 
				
				$content = str_replace(	'[[TERMSTITLE]]', 				$post->post_title,  $content );
				$content = str_replace(	'[[TERMS]]', 					$terms, 			$content );
				$content = str_replace(	'[[PDFREGISTEREDNAME]]', 		$pdfregisteredname, $content );
				$content = str_replace(	'[[PDFREGISTEREDADDRESS]]', 	$pdfregaddress, 	$content );
				$content = str_replace(	'[[PDFCOMPANYNUMBER]]', 		$pdfcompanynumber, 	$content );
				$content = str_replace(	'[[PDFTAXNUMBER]]', 			$pdftaxnumber, 		$content ); 
				
				return $content;	
			}

			/** 
			 * Get pdf template
			 * 
			 * Put your customized template in 
			 * wp-content/themes/YOUR_THEME/pdf_templates/template.php
			 *
			 * Windows hosting fixes
			 */
			function get_pdf_template( $filename ) {

				$plugin_version     = str_replace('/classes/','/templates/',plugin_dir_path(__FILE__) ) . $filename;
				$plugin_version     = str_replace('\classes/','\templates\\',$plugin_version);

                $theme_version_file = get_stylesheet_directory() . '/pdf_templates/' . $filename;

				$pos = strpos( $plugin_version, ":\\" );
				if ( $pos === false ) {

					$pdftemplate 		= file_exists($theme_version_file) ? $theme_version_file : $plugin_version;

				} else {
					$theme_version_file = str_replace('/', '\\', $theme_version_file );
					$plugin_version		= str_replace('/', '\\', $plugin_version );
					$pdftemplate 		= file_exists($theme_version_file) ? $theme_version_file : $plugin_version;
					$pdftemplate		= str_replace('/', '\\', $pdftemplate );
				}

				return $pdftemplate;

			} // get_pdf_template

			 /**
			  * Send a test PDF from the PDF Debugging settings
			  */
			function send_test_pdf() {
				 
				ob_start();

				include( PDFPLUGINPATH . "templates/pdftest.php" );
					
				$dompdf = new DOMPDF();
				$dompdf->load_html( $messagetext );
				$dompdf->set_paper( 'a4', 'portrait' );
				$dompdf->render();
						
				$attachments = sys_get_temp_dir() . '/testpdf.pdf';
					
				ob_clean();
				// Write the PDF to the TMP directory		
				file_put_contents( $attachments, $dompdf->output() );
					
				$emailsubject 	= __( 'Test Email with PDF Attachment', 'woocommerce-pdf-invoice' );
				$emailbody 		= __( 'A PDF should be attached to this email to confirm that the PDF is being created and attached correctly', 'woocommerce-pdf-invoice' );
					
				wp_mail( sanitize_email( $_POST['pdfemailtest-emailaddress'] ), $emailsubject , $emailbody , $headers='', $attachments );
				 
			}

        }

    	$GLOBALS['WC_send_pdf'] = new WC_send_pdf();