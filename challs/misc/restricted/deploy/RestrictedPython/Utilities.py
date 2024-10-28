##############################################################################
#
# Copyright (c) 2002 Zope Foundation and Contributors.
#
# This software is subject to the provisions of the Zope Public License,
# Version 2.1 (ZPL).  A copy of the ZPL should accompany this distribution.
# THIS SOFTWARE IS PROVIDED "AS IS" AND ANY AND ALL EXPRESS OR IMPLIED
# WARRANTIES ARE DISCLAIMED, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
# WARRANTIES OF TITLE, MERCHANTABILITY, AGAINST INFRINGEMENT, AND FITNESS
# FOR A PARTICULAR PURPOSE
#
##############################################################################


import string


utility_builtins = {}


class _AttributeDelegator:
    def __init__(self, mod, *excludes):
        """delegate attribute lookups outside *excludes* to module *mod*."""
        self.__mod = mod
        self.__excludes = excludes

    def __getattr__(self, attr):
        if attr in self.__excludes:
            raise NotImplementedError(
                f"{self.__mod.__name__}.{attr} is not safe")
        return getattr(self.__mod, attr)


utility_builtins['string'] = _AttributeDelegator(string, "Formatter", "Template")
