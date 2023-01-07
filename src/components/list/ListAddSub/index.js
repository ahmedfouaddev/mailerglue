import { __ } from '@wordpress/i18n';
import { render, Component, Fragment, useState, useCallback, useEffect, useRef } from '@wordpress/element';
import { useFocus } from '@helpers/use-focus';

import {
	__experimentalSpacer as Spacer,
	__experimentalHStack as HStack,
	__experimentalText as Text,
	Flex,
	FlexItem,
	FlexBlock,
} from '@wordpress/components';

import { theme } from '@data/theme';

import apiFetch from '@wordpress/api-fetch';

const ListAddSub = props => {

	const { list } = mailerglue_backend;

	const { sizes, fontweight, colors } = theme;

	return (
		<FlexBlock style={ { backgroundColor: colors.bg.gray1, padding: "32px", border: '1px solid #ededed' } }>
			<h3>Add subscribers to this list</h3>
		</FlexBlock>
	);

}

export default ListAddSub;