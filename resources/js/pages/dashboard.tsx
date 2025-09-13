import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';

interface Dataset {
    id: number;
    title: string;
    status: string;
    created_at: string;
    files_count: number;
    reviews_count: number;
}

interface Statistics {
    totalDatasets: number;
    totalUsers: number;
    totalLecturers: number;
    totalStudents: number;
    datasetsThisMonth: number;
    pendingReviews: number;
}

interface Props {
    userDatasets: Dataset[];
    statistics?: Statistics;
    recentDatasets: Dataset[];
    canViewReports: boolean;
    [key: string]: unknown;
}

export default function Dashboard({ userDatasets, statistics, recentDatasets, canViewReports }: Props) {
    return (
        <AppShell>
            <Head title="Dashboard" />
            
            <div className="max-w-7xl mx-auto px-4 py-8">
                <div className="mb-8">
                    <h1 className="text-3xl font-bold mb-4">📊 UISR Dashboard</h1>
                    <p className="text-gray-600 dark:text-gray-400">
                        Welcome to your research dataset management dashboard
                    </p>
                </div>

                {/* Statistics (for curators/admins) */}
                {statistics && (
                    <div className="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-6 mb-8">
                        <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <div className="text-3xl mb-2">📊</div>
                            <div className="text-2xl font-bold">{statistics.totalDatasets}</div>
                            <div className="text-sm text-gray-600 dark:text-gray-400">Total Datasets</div>
                        </div>
                        <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <div className="text-3xl mb-2">👥</div>
                            <div className="text-2xl font-bold">{statistics.totalUsers}</div>
                            <div className="text-sm text-gray-600 dark:text-gray-400">Total Users</div>
                        </div>
                        <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <div className="text-3xl mb-2">👨‍🏫</div>
                            <div className="text-2xl font-bold">{statistics.totalLecturers}</div>
                            <div className="text-sm text-gray-600 dark:text-gray-400">Lecturers</div>
                        </div>
                        <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <div className="text-3xl mb-2">👨‍🎓</div>
                            <div className="text-2xl font-bold">{statistics.totalStudents}</div>
                            <div className="text-sm text-gray-600 dark:text-gray-400">Students</div>
                        </div>
                        <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <div className="text-3xl mb-2">📈</div>
                            <div className="text-2xl font-bold">{statistics.datasetsThisMonth}</div>
                            <div className="text-sm text-gray-600 dark:text-gray-400">This Month</div>
                        </div>
                        <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <div className="text-3xl mb-2">⏳</div>
                            <div className="text-2xl font-bold">{statistics.pendingReviews}</div>
                            <div className="text-sm text-gray-600 dark:text-gray-400">Pending Reviews</div>
                        </div>
                    </div>
                )}

                <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {/* User's Datasets */}
                    <div className="bg-white dark:bg-gray-800 rounded-lg shadow">
                        <div className="p-6 border-b dark:border-gray-700">
                            <div className="flex items-center justify-between">
                                <h2 className="text-xl font-semibold">📂 My Datasets</h2>
                                <Link href={route('datasets.create')}>
                                    <Button size="sm">Submit New Dataset</Button>
                                </Link>
                            </div>
                        </div>
                        <div className="p-6">
                            {userDatasets.length > 0 ? (
                                <div className="space-y-4">
                                    {userDatasets.map((dataset) => (
                                        <div key={dataset.id} className="flex items-center justify-between p-4 border rounded-lg dark:border-gray-600">
                                            <div className="flex-1">
                                                <Link 
                                                    href={route('datasets.show', dataset.id)}
                                                    className="font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200"
                                                >
                                                    {dataset.title}
                                                </Link>
                                                <div className="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                    <span className={`inline-block px-2 py-1 text-xs rounded mr-2 ${
                                                        dataset.status === 'published' 
                                                            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                                            : dataset.status === 'under_review'
                                                            ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
                                                            : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'
                                                    }`}>
                                                        {dataset.status}
                                                    </span>
                                                    {dataset.files_count} files • {dataset.reviews_count} reviews
                                                </div>
                                            </div>
                                            <div className="text-sm text-gray-500 dark:text-gray-400">
                                                {new Date(dataset.created_at).toLocaleDateString()}
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            ) : (
                                <div className="text-center py-8">
                                    <div className="text-4xl mb-4">📊</div>
                                    <h3 className="text-lg font-medium mb-2">No datasets yet</h3>
                                    <p className="text-gray-600 dark:text-gray-400 mb-4">
                                        Start contributing to the research community by submitting your first dataset.
                                    </p>
                                    <Link href={route('datasets.create')}>
                                        <Button>Submit Your First Dataset</Button>
                                    </Link>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Recent Activity */}
                    <div className="bg-white dark:bg-gray-800 rounded-lg shadow">
                        <div className="p-6 border-b dark:border-gray-700">
                            <div className="flex items-center justify-between">
                                <h2 className="text-xl font-semibold">🕒 Recent Activity</h2>
                                <Link href={route('datasets.index')}>
                                    <Button variant="outline" size="sm">Browse All</Button>
                                </Link>
                            </div>
                        </div>
                        <div className="p-6">
                            {recentDatasets.length > 0 ? (
                                <div className="space-y-3">
                                    {recentDatasets.map((dataset) => (
                                        <div key={dataset.id} className="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <div className="text-lg">📊</div>
                                            <div className="flex-1 min-w-0">
                                                <Link 
                                                    href={route('datasets.show', dataset.id)}
                                                    className="text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200"
                                                >
                                                    {dataset.title}
                                                </Link>
                                                <div className="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                    Recently published
                                                </div>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            ) : (
                                <div className="text-center py-8">
                                    <div className="text-4xl mb-4">🕒</div>
                                    <p className="text-gray-600 dark:text-gray-400">No recent activity</p>
                                </div>
                            )}
                        </div>
                    </div>
                </div>

                {/* Quick Actions */}
                <div className="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 className="text-xl font-semibold mb-4">⚡ Quick Actions</h2>
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <Link href={route('datasets.create')}>
                            <div className="p-4 border rounded-lg hover:shadow-md transition-shadow dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-500">
                                <div className="text-2xl mb-2">📤</div>
                                <div className="font-medium">Submit Dataset</div>
                                <div className="text-sm text-gray-600 dark:text-gray-400">Upload new research data</div>
                            </div>
                        </Link>
                        
                        <Link href={route('datasets.index')}>
                            <div className="p-4 border rounded-lg hover:shadow-md transition-shadow dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-500">
                                <div className="text-2xl mb-2">🔍</div>
                                <div className="font-medium">Browse Datasets</div>
                                <div className="text-sm text-gray-600 dark:text-gray-400">Explore published datasets</div>
                            </div>
                        </Link>
                        
                        <Link href={route('profile.show')}>
                            <div className="p-4 border rounded-lg hover:shadow-md transition-shadow dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-500">
                                <div className="text-2xl mb-2">👤</div>
                                <div className="font-medium">My Profile</div>
                                <div className="text-sm text-gray-600 dark:text-gray-400">Manage your profile</div>
                            </div>
                        </Link>
                        
                        {canViewReports && (
                            <Link href={route('reports.index')}>
                                <div className="p-4 border rounded-lg hover:shadow-md transition-shadow dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-500">
                                    <div className="text-2xl mb-2">📈</div>
                                    <div className="font-medium">View Reports</div>
                                    <div className="text-sm text-gray-600 dark:text-gray-400">Accreditation reports</div>
                                </div>
                            </Link>
                        )}
                    </div>
                </div>
            </div>
        </AppShell>
    );
}
