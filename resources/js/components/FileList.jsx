import React, { useState, useEffect } from 'react';

function FileList({ files }) {
    return (
        <div>
            <h2>Lista de Arquivos CSV</h2>
            <ul>
                {files.length > 0 ? (
                    files.map((file, index) => (
                        <li key={index}>{file}</li>
                    ))
                ) : (
                    <li>Nenhum arquivo encontrado.</li>
                )}
            </ul>
        </div>
    );
}

export default FileList;
