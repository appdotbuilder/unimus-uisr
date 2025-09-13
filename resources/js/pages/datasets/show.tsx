import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';

interface Dataset {
    id: number;
    title: string;
    description: string;
    domain: string;
    task: string;
    license: string;
    doi?: string;
    access_level: string;
    collaboration_type: string;
    status: string;
    keywords?: string[];
    contributors?: string[];
    version: string;
    download_count: number;
    citation_count: number;
    published_at?: string;
    created_at: string;
    user: {
        id: number;
        name: string;
        profile?: {
            first_name: string;
            last_name: string;
            type: string;
            department: string;
            faculty: string;
            orcid?: string;
        };
    };
    files: Array<{
        id: number;
        filename: string;
        original_filename: string;
        formatted_size: string;
        extension: string;
        is_primary: boolean;
        metadata?: {
            rows?: number;
            columns?: number;
            encoding?: string;
            delimiter?: string;
        };
    }>;
}

interface Props {
    dataset: Dataset;
    canEdit: boolean;
    [key: string]: unknown;
}

export default function DatasetShow({ dataset, canEdit }: Props) {
    const [citationFormat, setCitationFormat] = useState<'apa' | 'ieee'>('apa');

    const generateCitation = (format: 'apa' | 'ieee') => {
        const author = dataset.user.profile 
            ? `${dataset.user.profile.last_name}, ${dataset.user.profile.first_name.charAt(0)}.`
            : dataset.user.name;
        
        const year = dataset.published_at ? new Date(dataset.published_at).getFullYear() : new Date().getFullYear();
        const title = dataset.title;
        const version = dataset.version;
        const doi = dataset.doi;
        
        if (format === 'apa') {
            let citation = `${author} (${year}). ${title} (Version ${version}) [Data set].`;
            if (doi) citation += ` https://doi.org/${doi}`;
            return citation;
        } else {
            let citation = `${author}, "${title}," Version ${version}, ${year}.`;
            if (doi) citation += ` doi: ${doi}`;
            return citation;
        }
    };

    const handleDownload = () => {
        // In a real implementation, this would trigger file download
        router.post(route('datasets.download', dataset.id), {}, {
            preserveState: true,
        });
    };

    const handleSubmitForReview = () => {
        router.patch(route('datasets.update', dataset.id), { status: 'submitted' }, {
            preserveState: true,
        });
    };

    return (
        <AppShell>
            <Head title={dataset.title} />
            
            <div className="max-w-4xl mx-auto px-4 py-8">
                {/* Header */}
                <div className="mb-8">
                    <div className="flex items-center justify-between mb-4">
                        <div className="flex items-center space-x-3">
                            <span className={`px-3 py-1 text-sm font-medium rounded-full ${
                                dataset.status === 'published' 
                                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                    : dataset.status === 'under_review'
                                    ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
                                    : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'
                            }`}>
                                {dataset.status === 'published' ? '✅ Published' :
                                 dataset.status === 'under_review' ? '⏳ Under Review' :
                                 dataset.status === 'draft' ? '📝 Draft' : dataset.status}
                            </span>
                            <span className="px-3 py-1 text-sm font-medium bg-blue-100 text-blue-800 rounded dark:bg-blue-900 dark:text-blue-200">
                                {dataset.domain}
                            </span>
                            <span className={`px-3 py-1 text-sm font-medium rounded ${
                                dataset.collaboration_type === 'international' 
                                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                    : dataset.collaboration_type === 'national'
                                    ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
                                    : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'
                            }`}>
                                {dataset.collaboration_type === 'international' ? '🌍 International' :
                                 dataset.collaboration_type === 'national' ? '🏛️ National' : '🏢 Local'}
                            </span>
                        </div>
                        
                        {canEdit && (
                            <div className="flex space-x-2">
                                <Link href={route('datasets.edit', dataset.id)}>
                                    <Button variant="outline" size="sm">Edit</Button>
                                </Link>
                                {dataset.status === 'draft' && (
                                    <Button onClick={handleSubmitForReview} size="sm">
                                        Submit for Review
                                    </Button>
                                )}
                            </div>
                        )}
                    </div>
                    
                    <h1 className="text-3xl font-bold mb-4">{dataset.title}</h1>
                    
                    <div className="text-gray-600 dark:text-gray-400 mb-4">
                        <div className="flex items-center space-x-4 text-sm">
                            <span>👤 {dataset.user.profile?.first_name} {dataset.user.profile?.last_name} || {dataset.user.name}</span>
                            <span>🎯 {dataset.task}</span>
                            <span>📅 {dataset.published_at ? new Date(dataset.published_at).toLocaleDateString() : 'Not published'}</span>
                        </div>
                    </div>
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {/* Main Content */}
                    <div className="lg:col-span-2 space-y-8">
                        {/* Description */}
                        <section className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <h2 className="text-xl font-semibold mb-4">📋 Description</h2>
                            <p className="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">
                                {dataset.description}
                            </p>
                        </section>

                        {/* Files */}
                        <section className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <h2 className="text-xl font-semibold mb-4">📁 Files</h2>
                            <div className="space-y-3">
                                {dataset.files.map((file) => (
                                    <div key={file.id} className="flex items-center justify-between p-3 border rounded-lg dark:border-gray-600">
                                        <div className="flex items-center space-x-3">
                                            <span className="text-2xl">
                                                {file.extension === 'csv' ? '📊' :
                                                 file.extension === 'json' ? '📄' :
                                                 file.extension === 'arff' ? '📋' : '📁'}
                                            </span>
                                            <div>
                                                <div className="font-medium">
                                                    {file.original_filename}
                                                    {file.is_primary && (
                                                        <span className="ml-2 px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded dark:bg-blue-900 dark:text-blue-200">
                                                            Primary
                                                        </span>
                                                    )}
                                                </div>
                                                <div className="text-sm text-gray-500 dark:text-gray-400">
                                                    {file.formatted_size} • {file.extension.toUpperCase()}
                                                    {file.metadata?.rows && (
                                                        <span> • {file.metadata.rows} rows</span>
                                                    )}
                                                    {file.metadata?.columns && (
                                                        <span> • {file.metadata.columns} columns</span>
                                                    )}
                                                </div>
                                            </div>
                                        </div>
                                        {dataset.status === 'published' && (
                                            <Button size="sm" onClick={handleDownload}>
                                                Download
                                            </Button>
                                        )}
                                    </div>
                                ))}
                            </div>
                        </section>

                        {/* Citation */}
                        {dataset.status === 'published' && (
                            <section className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                                <h2 className="text-xl font-semibold mb-4">📖 Cite This Dataset</h2>
                                <div className="mb-4">
                                    <div className="flex space-x-4 mb-3">
                                        <button
                                            onClick={() => setCitationFormat('apa')}
                                            className={`px-3 py-1 text-sm rounded ${
                                                citationFormat === 'apa' 
                                                    ? 'bg-blue-600 text-white' 
                                                    : 'bg-gray-200 text-gray-700 dark:bg-gray-600 dark:text-gray-300'
                                            }`}
                                        >
                                            APA
                                        </button>
                                        <button
                                            onClick={() => setCitationFormat('ieee')}
                                            className={`px-3 py-1 text-sm rounded ${
                                                citationFormat === 'ieee' 
                                                    ? 'bg-blue-600 text-white' 
                                                    : 'bg-gray-200 text-gray-700 dark:bg-gray-600 dark:text-gray-300'
                                            }`}
                                        >
                                            IEEE
                                        </button>
                                    </div>
                                    <div className="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg font-mono text-sm">
                                        {generateCitation(citationFormat)}
                                    </div>
                                </div>
                            </section>
                        )}
                    </div>

                    {/* Sidebar */}
                    <div className="space-y-6">
                        {/* Metadata */}
                        <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <h3 className="text-lg font-semibold mb-4">ℹ️ Metadata</h3>
                            <dl className="space-y-3 text-sm">
                                <div>
                                    <dt className="font-medium text-gray-900 dark:text-gray-100">License</dt>
                                    <dd className="text-gray-600 dark:text-gray-400">{dataset.license}</dd>
                                </div>
                                {dataset.doi && (
                                    <div>
                                        <dt className="font-medium text-gray-900 dark:text-gray-100">DOI</dt>
                                        <dd className="text-gray-600 dark:text-gray-400 break-all">{dataset.doi}</dd>
                                    </div>
                                )}
                                <div>
                                    <dt className="font-medium text-gray-900 dark:text-gray-100">Version</dt>
                                    <dd className="text-gray-600 dark:text-gray-400">{dataset.version}</dd>
                                </div>
                                <div>
                                    <dt className="font-medium text-gray-900 dark:text-gray-100">Access Level</dt>
                                    <dd className="text-gray-600 dark:text-gray-400 capitalize">{dataset.access_level}</dd>
                                </div>
                                <div>
                                    <dt className="font-medium text-gray-900 dark:text-gray-100">Downloads</dt>
                                    <dd className="text-gray-600 dark:text-gray-400">{dataset.download_count}</dd>
                                </div>
                                <div>
                                    <dt className="font-medium text-gray-900 dark:text-gray-100">Citations</dt>
                                    <dd className="text-gray-600 dark:text-gray-400">{dataset.citation_count}</dd>
                                </div>
                            </dl>
                        </div>

                        {/* Keywords */}
                        {dataset.keywords && dataset.keywords.length > 0 && (
                            <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                                <h3 className="text-lg font-semibold mb-4">🏷️ Keywords</h3>
                                <div className="flex flex-wrap gap-2">
                                    {dataset.keywords.map((keyword, index) => (
                                        <span
                                            key={index}
                                            className="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded dark:bg-gray-700 dark:text-gray-300"
                                        >
                                            {keyword}
                                        </span>
                                    ))}
                                </div>
                            </div>
                        )}

                        {/* Contributors */}
                        {dataset.contributors && dataset.contributors.length > 0 && (
                            <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                                <h3 className="text-lg font-semibold mb-4">🤝 Contributors</h3>
                                <ul className="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                    {dataset.contributors.map((contributor, index) => (
                                        <li key={index}>{contributor}</li>
                                    ))}
                                </ul>
                            </div>
                        )}

                        {/* Author Info */}
                        <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <h3 className="text-lg font-semibold mb-4">👤 Author</h3>
                            <div className="text-sm">
                                <div className="font-medium">
                                    {dataset.user.profile?.first_name} {dataset.user.profile?.last_name} || {dataset.user.name}
                                </div>
                                {dataset.user.profile && (
                                    <>
                                        <div className="text-gray-600 dark:text-gray-400 capitalize">
                                            {dataset.user.profile.type}
                                        </div>
                                        <div className="text-gray-600 dark:text-gray-400">
                                            {dataset.user.profile.department}
                                        </div>
                                        <div className="text-gray-600 dark:text-gray-400">
                                            {dataset.user.profile.faculty}
                                        </div>
                                        {dataset.user.profile.orcid && (
                                            <div className="text-gray-600 dark:text-gray-400">
                                                ORCID: {dataset.user.profile.orcid}
                                            </div>
                                        )}
                                        <div className="mt-2">
                                            <Link
                                                href={route('profiles.show', dataset.user.id)}
                                                className="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200"
                                            >
                                                View Profile →
                                            </Link>
                                        </div>
                                    </>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AppShell>
    );
}