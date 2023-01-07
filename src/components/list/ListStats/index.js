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

const ListStats = props => {

	const { list } = mailerglue_backend;

	const { sizes, fontweight, colors, gaps } = theme;

	return (
		<FlexBlock style={{ backgroundColor: colors.bg.gray1, display: "flex", alignItems: "center", justifyContent: "center", padding: "40px 0" }}>
			<Flex gap={gaps.s} align="center" justify="space-between">

				<FlexBlock>
					<Text color={colors.muted} isBlock>Subscribers</Text>
					<Text size={sizes.big} weight={fontweight.medium} isBlock><a href="#">{ list.sub_count }</a></Text>
				</FlexBlock>

				<FlexBlock>
					<Text color={colors.muted} isBlock>Email sent</Text>
					<Text size={sizes.big} weight={fontweight.medium} isBlock><a href="#">{ list.emails_sent }</a></Text>
				</FlexBlock>

				<FlexBlock>
					<Text color={colors.muted} isBlock>Delivered</Text>
					<Text size={sizes.big} weight={fontweight.medium} isBlock>{ list.delivered }</Text>
				</FlexBlock>

				<FlexBlock>
					<Text color={colors.muted} isBlock>Opened</Text>
					<Text size={sizes.big} weight={fontweight.medium} isBlock>{ list.open_rate }</Text>
				</FlexBlock>

				<FlexBlock>
					<Text color={colors.muted} isBlock>Clicked</Text>
					<Text size={sizes.big} weight={fontweight.medium} isBlock>{ list.click_rate }</Text>
				</FlexBlock>

			</Flex>
		</FlexBlock>
	);

}

export default ListStats;