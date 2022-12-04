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

const Connect = props => {

	const { state, setState, updateState } = props;

	const { api_url, words } = mailerglue_backend;

	const [ emailInputRef, setEmailInputRef ] = useFocus();

	const doAPI = (e) => {

		updateState( 'sending', true );

		apiFetch( {
			path: api_url + '/verify_login',
			method: 'post',
			data: {
				email: state.loginEmail,
				password: state.loginPassword,
			},
		} ).then(
			(response) => {
				if ( ! response.success ) {
					updateState( 'array', { sending: false, errors: { login: response.message }, access_token: '', loginPassword: '' } );
					setEmailInputRef();
				} else {
					updateState( 'array', { sending: false, errors: { login: '' }, access_token: response, from_name: response.name, from_email: response.email } );
					props.history.push('/settings');
				}
			},
			(error) => {
				updateState( 'array', { sending: false, errors: { login: error.message }, access_token: '', loginPassword: '' } );
			}
		);

	};

	useEffect(() => {
		setEmailInputRef();
	}, []);

	return (
		<>

		<Heading level={5} className="mailerglue-text-regular">{ words.welcome }</Heading>

		<Heading level={2}>{ words.login_heading }</Heading>

		<p className="mailerglue-text-bigger">
			{ words.no_account_yet } <Link to="/signup">{ words.signup }</Link>
		</p>

		<Spacer paddingTop={10} marginBottom={0} />

		{ state.errors.login &&
		<Notice status="error" isDismissible={false}>
			<p>{ state.errors.login }</p>
		</Notice> }

		<form action="/">
		<PanelBody className="mailerglue-panelbody-form">
			<PanelRow>
				<InputControl
					placeholder={ words.email_label }
					value={ state.loginEmail }
					onChange={ (value) => { updateState( 'loginEmail', value ) } }
					ref={ emailInputRef }
				/>
			</PanelRow>
			<PanelRow>
				<InputControl
					placeholder={ words.password_label }
					type="password"
					value={ state.loginPassword }
					onChange={ (value) => { updateState( 'loginPassword', value ) } }
				/>
			</PanelRow>
			<Spacer paddingTop={3} marginBottom={0} />
			<PanelRow>
				<Button
					isPrimary
					type="submit"
					disabled={ ! state.loginEmail || ! state.loginPassword || state.sending }
					isBusy={ state.sending }
					onClick={ doAPI }
					>
					{ words.connect }
				</Button>
			</PanelRow>
		</PanelBody>
		</form>

		</>
	);

}

export default withRouter(Connect);