import React from 'react';
import { Head, router } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';

interface DatasetFormData {
    title: string;
    description: string;
    domain: string;
    task: string;
    license: string;
    doi: string;
    access_level: string;
    collaboration_type: string;
    keywords: string;
    contributors: string;
    version: string;
    dataset_file: File | null;
    [key: string]: unknown;
}

export default function CreateDataset() {
    const [data, setData] = React.useState<DatasetFormData>({
        title: '',
        description: '',
        domain: '',
        task: '',
        license: 'CC BY 4.0',
        doi: '',
        access_level: 'public',
        collaboration_type: 'local',
        keywords: '',
        contributors: '',
        version: '1.0',
        dataset_file: null,
    });
    const [processing, setProcessing] = React.useState(false);
    const [errors, setErrors] = React.useState<Record<string, string>>({});

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        setProcessing(true);
        
        const formData = new FormData();
        Object.keys(data).forEach(key => {
            const value = data[key as keyof DatasetFormData];
            if (value !== null && value !== undefined) {
                if (key === 'dataset_file' && value instanceof File) {
                    formData.append(key, value);
                } else {
                    formData.append(key, String(value));
                }
            }
        });

        router.post(route('datasets.store'), formData, {
            onSuccess: () => {
                setProcessing(false);
            },
            onError: (errors) => {
                setErrors(errors as Record<string, string>);
                setProcessing(false);
            },
        });
    };

    const updateData = (key: keyof DatasetFormData, value: DatasetFormData[keyof DatasetFormData]) => {
        setData(prev => ({ ...prev, [key]: value }));
    };

    const domains = [
        'Machine Learning', 'Data Mining', 'Computer Vision', 'Natural Language Processing',
        'Healthcare', 'Finance', 'Education', 'Social Sciences', 'Bioinformatics',
        'Climate Science', 'Economics', 'Psychology'
    ];

    const tasks = [
        'Classification', 'Regression', 'Clustering', 'Anomaly Detection',
        'Time Series Analysis', 'Text Analysis', 'Image Recognition',
        'Sentiment Analysis', 'Recommendation Systems', 'Prediction'
    ];

    const licenses = [
        'CC BY 4.0', 'CC BY-SA 4.0', 'CC BY-NC 4.0', 'CC BY-NC-SA 4.0',
        'MIT', 'GPL-3.0', 'Apache 2.0', 'BSD 3-Clause'
    ];

    return (
        <AppShell>
            <Head title="Submit New Dataset" />
            
            <div className="max-w-4xl mx-auto px-4 py-8">
                <div className="mb-8">
                    <h1 className="text-3xl font-bold mb-4">📤 Submit New Dataset</h1>
                    <p className="text-gray-600 dark:text-gray-400">
                        Share your research data with the academic community
                    </p>
                </div>

                <form onSubmit={handleSubmit} className="bg-white dark:bg-gray-800 rounded-lg shadow p-8 space-y-6">
                    {/* Basic Information */}
                    <div>
                        <h2 className="text-xl font-semibold mb-4">📋 Basic Information</h2>
                        
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div className="md:col-span-2">
                                <label className="block text-sm font-medium mb-2">
                                    Dataset Title *
                                </label>
                                <input
                                    type="text"
                                    value={data.title}
                                    onChange={(e) => updateData('title', e.target.value)}
                                    placeholder="Enter a descriptive title for your dataset"
                                    className="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600"
                                    required
                                />
                                {errors.title && <div className="text-red-500 text-sm mt-1">{errors.title}</div>}
                            </div>

                            <div className="md:col-span-2">
                                <label className="block text-sm font-medium mb-2">
                                    Description *
                                </label>
                                <textarea
                                    value={data.description}
                                    onChange={(e) => updateData('description', e.target.value)}
                                    placeholder="Provide a detailed description of your dataset (minimum 50 characters)"
                                    rows={6}
                                    className="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600"
                                    required
                                />
                                {errors.description && <div className="text-red-500 text-sm mt-1">{errors.description}</div>}
                            </div>

                            <div>
                                <label className="block text-sm font-medium mb-2">
                                    Research Domain *
                                </label>
                                <select
                                    value={data.domain}
                                    onChange={(e) => updateData('domain', e.target.value)}
                                    className="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600"
                                    required
                                >
                                    <option value="">Select a domain</option>
                                    {domains.map((domain) => (
                                        <option key={domain} value={domain}>{domain}</option>
                                    ))}
                                </select>
                                {errors.domain && <div className="text-red-500 text-sm mt-1">{errors.domain}</div>}
                            </div>

                            <div>
                                <label className="block text-sm font-medium mb-2">
                                    Research Task *
                                </label>
                                <select
                                    value={data.task}
                                    onChange={(e) => updateData('task', e.target.value)}
                                    className="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600"
                                    required
                                >
                                    <option value="">Select a task</option>
                                    {tasks.map((task) => (
                                        <option key={task} value={task}>{task}</option>
                                    ))}
                                </select>
                                {errors.task && <div className="text-red-500 text-sm mt-1">{errors.task}</div>}
                            </div>
                        </div>
                    </div>

                    {/* File Upload */}
                    <div>
                        <h2 className="text-xl font-semibold mb-4">📁 Dataset File</h2>
                        
                        <div>
                            <label className="block text-sm font-medium mb-2">
                                Upload Dataset File *
                            </label>
                            <input
                                type="file"
                                accept=".csv,.json,.arff"
                                onChange={(e) => updateData('dataset_file', e.target.files?.[0] || null)}
                                className="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600"
                                required
                            />
                            <div className="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                Supported formats: CSV, JSON, ARFF. Maximum file size: 50MB
                            </div>
                            {errors.dataset_file && <div className="text-red-500 text-sm mt-1">{errors.dataset_file}</div>}
                        </div>
                    </div>

                    {/* Metadata */}
                    <div>
                        <h2 className="text-xl font-semibold mb-4">ℹ️ Metadata</h2>
                        
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label className="block text-sm font-medium mb-2">
                                    License *
                                </label>
                                <select
                                    value={data.license}
                                    onChange={(e) => updateData('license', e.target.value)}
                                    className="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600"
                                >
                                    {licenses.map((license) => (
                                        <option key={license} value={license}>{license}</option>
                                    ))}
                                </select>
                                {errors.license && <div className="text-red-500 text-sm mt-1">{errors.license}</div>}
                            </div>

                            <div>
                                <label className="block text-sm font-medium mb-2">
                                    Access Level *
                                </label>
                                <select
                                    value={data.access_level}
                                    onChange={(e) => updateData('access_level', e.target.value)}
                                    className="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600"
                                >
                                    <option value="public">🌍 Public</option>
                                    <option value="restricted">🔒 Restricted</option>
                                    <option value="private">🔐 Private</option>
                                </select>
                                {errors.access_level && <div className="text-red-500 text-sm mt-1">{errors.access_level}</div>}
                            </div>

                            <div>
                                <label className="block text-sm font-medium mb-2">
                                    Collaboration Type *
                                </label>
                                <select
                                    value={data.collaboration_type}
                                    onChange={(e) => updateData('collaboration_type', e.target.value)}
                                    className="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600"
                                >
                                    <option value="local">🏢 Local</option>
                                    <option value="national">🏛️ National</option>
                                    <option value="international">🌍 International</option>
                                </select>
                                {errors.collaboration_type && <div className="text-red-500 text-sm mt-1">{errors.collaboration_type}</div>}
                            </div>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label className="block text-sm font-medium mb-2">
                                    DOI (Optional)
                                </label>
                                <input
                                    type="text"
                                    value={data.doi}
                                    onChange={(e) => updateData('doi', e.target.value)}
                                    placeholder="e.g., 10.1000/123"
                                    className="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600"
                                />
                                {errors.doi && <div className="text-red-500 text-sm mt-1">{errors.doi}</div>}
                            </div>

                            <div>
                                <label className="block text-sm font-medium mb-2">
                                    Version
                                </label>
                                <input
                                    type="text"
                                    value={data.version}
                                    onChange={(e) => updateData('version', e.target.value)}
                                    placeholder="e.g., 1.0"
                                    className="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600"
                                />
                                {errors.version && <div className="text-red-500 text-sm mt-1">{errors.version}</div>}
                            </div>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label className="block text-sm font-medium mb-2">
                                    Keywords (Optional)
                                </label>
                                <input
                                    type="text"
                                    value={data.keywords}
                                    onChange={(e) => updateData('keywords', e.target.value)}
                                    placeholder="machine learning, classification, health (comma-separated)"
                                    className="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600"
                                />
                                {errors.keywords && <div className="text-red-500 text-sm mt-1">{errors.keywords}</div>}
                            </div>

                            <div>
                                <label className="block text-sm font-medium mb-2">
                                    Contributors (Optional)
                                </label>
                                <input
                                    type="text"
                                    value={data.contributors}
                                    onChange={(e) => updateData('contributors', e.target.value)}
                                    placeholder="John Doe, Jane Smith (comma-separated)"
                                    className="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600"
                                />
                                {errors.contributors && <div className="text-red-500 text-sm mt-1">{errors.contributors}</div>}
                            </div>
                        </div>
                    </div>

                    {/* Submit Button */}
                    <div className="pt-6 border-t dark:border-gray-700">
                        <div className="flex justify-end space-x-4">
                            <Button
                                type="button"
                                variant="outline"
                                onClick={() => window.history.back()}
                            >
                                Cancel
                            </Button>
                            <Button
                                type="submit"
                                disabled={processing}
                            >
                                {processing ? 'Submitting...' : 'Submit Dataset'}
                            </Button>
                        </div>
                    </div>
                </form>
            </div>
        </AppShell>
    );
}