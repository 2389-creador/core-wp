<?php
/**
 * Get JetEngine component/module items endpoint
 */
class Jet_Engine_Rest_Get_Items extends Jet_Engine_Base_API_Endpoint {

	/**
	 * Returns route name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'get-items';
	}

	/**
	 * API callback
	 *
	 * @return void
	 */
	public function callback( $request ) {

		$params = $request->get_params();
		$instance = ! empty( $params['instance'] ) ? $params['instance'] : false;

		if ( ! $instance ) {
			return rest_ensure_response( array(
				'success' => false,
				'message' => __( 'Item instance should be specified in request to correctly attach callbacks', 'jet-engine' ),
			) );
		}

		$res = apply_filters( 'jet-engine/rest-api/get-items/' . $instance, false, $params, $this );

		if ( ! $res ) {
			return rest_ensure_response( array(
				'success' => false,
				'message' => __( 'Callback not attached properly or success was not thrown during callback', 'jet-engine' ),
			) );
		}

		return $res;

	}

	/**
	 * Prepare post type item to return
	 *
	 * @param  array $item Item data
	 * @return array
	 */
	public function prepare_item( $item ) {

		$item['args']   = maybe_unserialize( $item['args'] );
		$item['labels'] = maybe_unserialize( $item['labels'] );

		return $item;
	}

	/**
	 * Returns endpoint request method - GET/POST/PUT/DELTE
	 *
	 * @return string
	 */
	public function get_method() {
		return 'GET';
	}

	/**
	 * Check user access to current end-popint
	 *
	 * @return bool
	 */
	public function permission_callback( $request ) {
		return current_user_can( 'manage_options' );
	}

}
