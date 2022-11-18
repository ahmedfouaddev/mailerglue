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

export const OnboardingConnect = props => {

	const { admin_first_name } = mailerglue_backend;

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

			setIsSending(false);

			if ( ! response.success ) {
				setErrorMsg( response.message );
			} else {
				setErrorMsg('');
			}

		} );

	};

	return (
		<>

		{ admin_first_name ? 
			<Heading level={4} className="mailerglue-text-regular">Welcome, { admin_first_name}!</Heading> : 
			<Heading level={4} className="mailerglue-text-regular">Welcome!</Heading>
		}

		<Heading level={2}>Let's begin by connecting your Mailer Glue account</Heading>

		<p className="mailerglue-text-bigger">
			Enter your Mailer Glue account details below to connect.
		</p>

		<Spacer paddingTop={10} marginBottom={0} />

		{ errorMsg &&
		<Notice status="error" isDismissible={false}>
			<p>{ errorMsg }</p>
		</Notice> }

		<PanelBody className="mailerglue-panelbody-form">
			<PanelRow>
				<InputControl
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
					isBusy={ false }
					onClick={ signInRequest }
					>
					{ __( 'Connect your Mailer Glue account', 'mailerglue' ) }
				</Button>
			</PanelRow>
		</PanelBody>

		</>
	);

}