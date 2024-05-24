import React from 'react';

const PreLoader = ({ progress }) => {
    const containerStyle = {
        marginTop: '10px',
        position: 'relative',
        width: '100%',
        height: '20px',
        border: '1px solid #ccc'
    };

    const progressBarStyle = {
        width: `${progress}%`,
        height: '100%',
        backgroundColor: '#007bff',
        borderRadius: '4px'
    };

    const textStyle = {
        textAlign: 'center',
        marginTop: '5px'
    };

    return (
        <div style={containerStyle}>
            <div style={progressBarStyle}></div>
            <p style={textStyle}>{progress}% concluído</p>
        </div>
    );
};

export default PreLoader;
