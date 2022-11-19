import { __ } from '@wordpress/i18n';

import apiFetch from '@wordpress/api-fetch';

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
} from '@wordpress/components';

import { withRouter } from 'react-router';

const OnboardingConnect = props => {

	const {admin_first_name, access_token} = mailerglue_backend;

	const [accessToken, setAccessToken] = useState(access_token);
	const [userEmail, setUserEmail] = useState('');
	const [userPassword, setUserPassword] = useState('');
	const [errorMsg, setErrorMsg] = useState('');
	const [isSending, setIsSending] = useState(false);

	const signInRequest = (e) => {

		setIsSending(true);

		apiFetch( {
			path: mailerglue_backend.api_url + '/verify_login',
			method: 'post',
			data: {
				email: userEmail,
				password: userPassword,
			},
		} ).then( response => {

			console.log( response );

			if ( ! response.success ) {
				setIsSending(false);
				setErrorMsg( response.message );
				setAccessToken('');
			} else {
				setErrorMsg('');
				setAccessToken( response );
				props.history.push('/settings');
			}

		} );

	};

	return (
		<>

		{ admin_first_name ? 
			<Heading level={5} className="mailerglue-text-regular">Welcome, { admin_first_name}!</Heading> : 
			<Heading level={5} className="mailerglue-text-regular">Welcome!</Heading>
		}

		<Heading level={2}>Let's begin by connecting your Mailer Glue account</Heading>

		<p className="mailerglue-text-bigger">
			Don't have an account yet? <a href="#">Sign up</a>
		</p>

		<Spacer paddingTop={10} marginBottom={0} />

		{ errorMsg &&
		<Notice status="error" isDismissible={false}>
			<p>{ errorMsg }</p>
		</Notice> }

		<PanelBody className="mailerglue-panelbody-form">
			<PanelRow>
				<InputControl
					autoFocus
					placeholder={ __( 'Email address', 'mailerglue' ) }
					value={ userEmail }
					onChange={
						( value ) => {
							setUserEmail( value );
						}
					}
				/>
			</PanelRow>
			<PanelRow>
				<InputControl
					placeholder={ __( 'Password', 'mailerglue' ) }
					type="password"
					value={ userPassword }
					onChange={
						( value ) => {
							setUserPassword( value );
						}
					}
				/>
			</PanelRow>
			<Spacer paddingTop={3} marginBottom={0} />
			<PanelRow>
				<Button
					isPrimary
					disabled={ ! userEmail || ! userPassword || isSending }
					isBusy={ isSending }
					onClick={ signInRequest }
					>
					{ __( 'Connect your Mailer Glue account', 'mailerglue' ) }
				</Button>
			</PanelRow>
		</PanelBody>

		</>
	);

}

export default withRouter(OnboardingConnect);