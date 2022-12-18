import { __ } from '@wordpress/i18n';

import { render, Component, Fragment, useState, useCallback, useEffect, useRef } from '@wordpress/element';

import {
	__experimentalHeading as Heading,
	__experimentalDivider as Divider,
	__experimentalSpacer as Spacer,
	__experimentalInputControl as InputControl,
	PanelBody,
	PanelRow,
	Button,
	Notice,
	ExternalLink
} from '@wordpress/components';

import { withRouter } from 'react-router';
import { Link } from 'react-router-dom';

import apiFetch from '@wordpress/api-fetch';

import { useFocus } from '@helpers/use-focus';

const Activate = props => {

	const { state, setState, updateState } = props;

	const { api_url } = mailerglue_backend;

	const [ setInput, setInputRef ] = useFocus();

	const doAPI = (e) => {

		event.preventDefault();

		updateState( 'sending', true );

		apiFetch( {
			path: api_url + '/activate',
			method: 'post',
			data: {
				code: state.activationCode,
			},
			headers: {
				'MailerGlue-Access-Token' : state.access_token.token,
				'MailerGlue-Account-ID' : state.access_token.id,
			},
		} ).then(
			( response ) => {
				console.log( response );
				if ( ! response.success ) {
					updateState( 'array', { sending: false, errors: { activate: response.message } } );
					setInputRef();
				} else {
					updateState( 'array', { sending: false, errors: { activate: '' } } );
					props.history.push( '/settings' );
				}
			},
			( error ) => {
				updateState( 'array', { sending: false, errors: { activate: error.message } } );
			}
		);

	};

	useEffect(() => {
		setInputRef();
	}, []);

	return (
		<>

		<Heading level={5} className="mailerglue-text-regular">Almost there!</Heading>

		<Heading level={2}>Validate your account</Heading>

		<p className="mailerglue-text-bigger">
			We have sent 6-digits activation code to your e-mail. Please enter it below to validate your account.
		</p>

		<Spacer paddingTop={2} marginBottom={0} />

		{ state.errors.activate &&
		<Notice status="error" isDismissible={false}>
			<p>{ state.errors.activate }</p>
		</Notice> }

		<form action="/">
		<PanelBody className="mailerglue-panelbody-form">
			<PanelRow>
				<InputControl
					value={ state.activationCode }
					onChange={ (value) => { updateState( 'activationCode', value ) } }
					ref={ setInput }
				/>
			</PanelRow>

			<PanelRow>
				<Button
					isPrimary
					type="submit"
					disabled={ ! state.activationCode }
					isBusy={ state.sending }
					onClick={ doAPI }
					>
					Validate
				</Button>
			</PanelRow>
		</PanelBody>
		</form>

		</>
	);

}

export default withRouter(Activate);