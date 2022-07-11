const {
	ServerSideRender,
	Disabled
} = wp.components;

const Edit = function( props ) {

	const {
		attributes,
	} = props;

	return <Disabled>
		<ServerSideRender
			block="jet-engine/profile-content"
			attributes={ attributes }
			urlQueryArgs={ {} }
		/>
	</Disabled>;
}

export default Edit;
